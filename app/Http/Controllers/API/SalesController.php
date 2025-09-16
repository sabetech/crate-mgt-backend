<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Sale;
use App\Events\SalesOrderCreated;
use App\Reports\DailySalesReport;
use Illuminate\Support\Facades\Auth;
use App\Constants\POS as POS_Modes;

class SalesController extends Controller
{
    //
    public function sales(Request $request) {
        $user_id = Auth::user()->id;

        $sales = Order::with(['customer', 'sales' => function($query) {
            $query->with('product');
        }])->where('user_id', $user_id)->get();

        return response()->json([
            "success" => true,
            "data" => $sales
        ]);
    }

    public function pay(Request $request) {
        Log::info($request->all());

        $customerId = $request->get('customer');
        $customer = Customer::find($customerId);

        $orderTransactionId = $request->get('order_transaction_id');

        $posMode = $request->get('posMode');

        $data = $request->all();
        //based on payment mode

        $userId = Auth::user()->id;

        if ($posMode == POS_Modes::CUSTOMER_SALE) {
            $this->handleCustomerPosOrder($data, $customer, $orderTransactionId, $userId);
        }

        if ($posMode == POS_Modes::VSE_LOADOUT) {
            $this->handleVseLoadoutOrder($data, $customer, $orderTransactionId, $userId);
        }

    }

    private function handleCustomerPosOrder($data, $customer, $orderTransactionId, $userId) {
        $order = Order::where('transaction_id', $orderTransactionId)->first();

        if ($order) {
            $order->amount_tendered = $data['amountTendered'];
            $order->date = $data['date'];
            $order->user_id = $userId;
            $order->status = 'approved';

            $order->save();
            $order->quantity = $order->quantity();

            SalesOrderCreated::dispatch($order);

            return response()->json([
                "success" => true,
                "data" => $order
            ]);
        }

        //based on POS mode ...

        $order = new Order;
        $saleItems = $data['sales'];
        $paymentType = $data['paymentType'];

        $totalAmount = $data['total'];
        $order->amount_tendered = 0; //this is initially 0 since payment has not been made!
        $order->date = $data['date'];
        $order->customer_id = $customer->id;
        $order->total_amount = $totalAmount;
        $order->payment_type = $paymentType;
        $order->order_type = 'sale';

        $order->user_id = $userId;

        $saleItems = json_decode($data['sales'], false);

        if ($customer->customer_type == 'wholesaler'){
            $order->transaction_id = "OPK-WHL-".time()."-".$customer->id;
        }else{
            $order->transaction_id = "OPK-RET-".time()."-".$customer->id;
        }

        $order->save();

        Log::info($saleItems);

        foreach($saleItems as $saleItem) {

            Sale::create(
                [
                    'order_id' => $order->id,
                    'product_id' => $saleItem->product->id,
                    'discount' => 0,
                    'quantity' => $saleItem->quantity,
                    'unit_price' => $saleItem->product->retail_price,
                    'sub_total' => $saleItem->quantity * $saleItem->product->retail_price,
                    'user_id' => $order->user_id
                ]
            );
        }
        return response()->json([
            "success" => true,
            "data" => $order
        ]);
    }

    private function handleVseLoadoutOrder($data, Customer $customer, $orderTransactionId, $user_id) {
        $order = Order::where('transaction_id', $orderTransactionId)->first();

        if ($order) {
            $order->amount_tendered = $data['amountTendered'];
            $order->date = $data['date'];
            $order->user_id = $user_id;
            $order->status = 'approved';

            $order->save();
            $order->quantity = $order->quantity();

            SalesOrderCreated::dispatch($order);

            return response()->json([
                "success" => true,
                "data" => $order
            ]);
        }

        $order = new Order;
        $saleItems = json_decode($data['sales'], false);

        $order->amount_tendered = 0; //this is initially 0 since payment has not been made!
        $order->date = $data['date'];
        $order->customer_id = $customer->id;

        $order->total_amount = array_reduce($saleItems, function ($carry, $item) {
            $carry += ($item->quantity * $item->product->retail_price);
            return $carry;
        });

        $order->user_id = $user_id;
        $order->order_type = 'vse';

        $order->transaction_id = "OPK-VSE-".time()."-".$customer->id;
        $order->save();

        foreach($saleItems as $saleItem) {

            Sale::create(
                [
                    'order_id' => $order->id,
                    'product_id' => $saleItem->product->id,
                    'discount' => 0,
                    'quantity' => $saleItem->quantity,
                    'unit_price' => $saleItem->product->retail_price,
                    'sub_total' => $saleItem->quantity * $saleItem->product->retail_price,
                    'user_id' => $order->user_id
                ]
            );
        }
    }

    public function salesReport(Request $request){

        $from = $request->get('from');
        $to = $request->get('to');

        $customerOption = $request->get('customerOption');
        $dailySalesReport = new DailySalesReport($from, $to, $customerOption);

        $salesBuilder = $dailySalesReport->generate();
        $sales = $salesBuilder->get();

        return response()->json([
            "success" => true,
            "data" => $sales
        ]);
    }

    public function getVseLoadoutOrderWithSaleItems($id, Request $request) {
        $customer = Customer::find($id);

        if (!$customer) return response()->json([
            "success" => false,
            "data" => "Customer not found"
        ]);

        Log::info("ID:::", [$id]);

        $order = Order::with(['sales' => function($query) {
            $query->with('product');
        }])->where('customer_id', $customer->id)
        ->where('order_type', 'vse')
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->first();

        return response()->json([
            "success" => true,
            "data" => $order
        ]);
    }

}

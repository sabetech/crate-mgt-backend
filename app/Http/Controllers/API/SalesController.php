<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Sale;
use Auth;

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
        $customerId = $request->get('customer');
        $customer = Customer::find($customerId);

        $orderTransactionId = $request->get('order_transaction_id');
        
        $order = Order::where('transaction_id', $orderTransactionId)->first();
        if ($order) {
            $order->amount_tendered = $amountTendered;
            $order->date = $date;
            $order->user_id = $user_id;
            $order->status = 'approved';

            $order->save();

            return response()->json([
                "success" => true,
                "data" => $order
            ]);
        }

        $order = new Order;
        $saleItems = $request->get('saleItems');
        $amountTendered = $request->get('amountTendered');
        $paymentType = $request->get('paymentType');
        $date = $request->get('date');
        $user_id = Auth::user()->id;
        $totalAmount = $request->get('total');
        $order->amount_tendered = $amountTendered; //this is initially 0 since payment has not been made!
        $order->date = $date;
        $order->customer_id = $customerId;
        $order->total_amount = $totalAmount;
        $order->payment_type = $paymentType;
        
        $order->user_id = $user_id;
        
        $saleItems = json_decode($saleItems, false);

        if ($customer->customer_type == 'wholesaler'){
            $order->transaction_id = "OPK-WHL-".time()."-".$customer->id;
        }else{
            $order->transaction_id = "OPK-RET-".time()."-".$customer->id;
        }
        
        $order->save();

        Log::info($saleItems);

        foreach($saleItems as $saleItem) {

            $sale = Sale::create(
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

            return response()->json([
                "success" => true,
                "data" => $order
            ]);
            //reduce inventory from here ...
            //track transactions here ... what ever this means

        }
    }
}

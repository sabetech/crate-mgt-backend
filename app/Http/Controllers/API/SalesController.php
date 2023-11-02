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
    public function pay(Request $request) {
        $customerId = $request->get('customer');
        $customer = Customer::find($customerId);

        $saleItems = $request->get('saleItems');
        $amountTendered = $request->get('amountTendered');
        $paymentType = $request->get('paymentType');
        $date = $request->get('date');
        $user_id = Auth::user()->id;
        $totalAmount = $request->get('total');

        Log::info("VARS", $request->all());

        $saleItems = json_decode($saleItems, false);

        $order = new Order;
        $order->customer_id = $customerId;
        $order->total_amount = $totalAmount;
        $order->amount_tendered = $amountTendered;
        $order->payment_type = $paymentType;
        $order->date = $date;
        $order->user_id = $user_id;

        if ($customer->customer_type == 'wholesaler'){
            $order->transaction_id = "OPK-WHL-".date("Ymd")."-".time()."-".$customer->id;
        }else{
            $order->transaction_id = "OPK-RET-".date("Ymd")."-".time()."-".$customer->id;
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

            //reduce inventory from here ...
            //track transactions here ... what ever this means

        }

    }
}

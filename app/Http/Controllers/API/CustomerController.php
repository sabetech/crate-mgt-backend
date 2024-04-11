<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerEmptiesAccount;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $requestType = $request->get('with-balance', false);
        $customerType = $request->get('customer_type', null);

        if (!$requestType) {
            if ($customerType) {
                $customers = Customer::where('customer_type', $customerType)->get();
            }else {
                $customers = Customer::all();
            }

            return response()->json([
                "success"=> true,
                "data" => $customers
            ]);
        }else{

            $customers = Customer::select();
            if ($customerType) {
                $customers = $customers->with('CustomerEmptiesAccount')->where('customer_type', $customerType)->get();
            }else{
                $customers = $customers->with('CustomerEmptiesAccount')->get();
            }

            return response()->json([
                "success"=>true,
                "data" => $customers
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $newCustomer = new Customer;
        $newCustomer->name = $request->get('customer_name');
        $newCustomer->phone = $request->get('phone_number');
        $newCustomer->customer_type = $request->get('customer_type');

        if ($newCustomer->save()) {
            return response()->json([
                "success" => true,
                "data" => "Customer was saved successfully"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function postReturnEmpties(Request $request) {

        $products = json_decode($request->get('products'));

        foreach ($products as $product) {

            $newReturnEmpties = new CustomerEmptiesAccount;
            $newReturnEmpties->customer_id = $request->customer;
            $newReturnEmpties->product_id = $product->product_id;
            $newReturnEmpties->quantity_transacted = $product->quantity;
            $newReturnEmpties->date = $request->date;

            $newReturnEmpties->save();

        }

        return response()->json([
            "success" => true,
            "data" => "Empties Returned by this customer has been saved successfully"
        ]);

    }

    public function getCustomerHistory(Request $request, string $id ) {
        $customer = Customer::find($id);
        $customerHistory = $customer->customerEmptiesAccount()
            ->with('product')
            ->orderBy('date', 'desc')
            ->orderBy('transaction_type', 'asc')
            ->get();

        return response()->json([
            "success" => true,
            "data" => $customerHistory
        ]);
    }

    public function getLoadoutInfoByVse($id, Request $request) {
        $date = $request->get('date');

        $VSE = Customer::where('id', $id)->with(['vseLoadout' =>
            function ($query) use ($date) {
                $query->where('date', $date)
                    ->with(['product']);
            }])->first();

        return response()->json([
            "success" => true,
            "data" => $VSE
        ]);
    }

    public function postRecordVseSales(Request $request, string $id) {
        $customer = Customer::find($id);

        if (!$customer)
            return response()->json([
                "success" => false,
                "data" => "Customer Does not exist!"
            ]);

        $product_quantities = json_decode($request->get('product_quantities'));

        foreach ($product_quantities as $product) {

            $customerEmptiesAccount = new CustomerEmptiesAccount;
            $customerEmptiesAccount->customer_id = $customer->id;

            $customerEmptiesAccount->product_id = $product->product;
            $customerEmptiesAccount->quantity_transacted = $product->quantity;

            $customerEmptiesAccount->transaction_type = 'in';

            $customerEmptiesAccount->date = date("Y-m-d", strtotime($request->date));
            $customerEmptiesAccount->save();
        }

        // foreach ($empties_returned as $product) {

        //     $customerEmptiesAccount = new CustomerEmptiesAccount;
        //     $customerEmptiesAccount->customer_id = $customer->id;

        //     $customerEmptiesAccount->product_id = $product->product;
        //     $customerEmptiesAccount->quantity_transacted = $product->quantity;

        //     $customerEmptiesAccount->transaction_type = 'in';

        //     $customerEmptiesAccount->date = date("Y-m-d", strtotime($request->date));
        //     $customerEmptiesAccount->save();
        // }


        return response()->json([
            "success" => true,
            "data" => "Customer VSE Sales was recorded successfully"
        ]);
    }
}

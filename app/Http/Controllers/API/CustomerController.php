<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $requestType = $request->get('with-balance', false);

        if (!$requestType) {
            $customers = Customer::all();

            return response()->json([
                "success"=> true,
                "data" => $customers
            ]);
        }else{
            $customers = Customer::with('CustomerEmptiesAccount')->get();

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

    public function returnEmpties(Request $request) {
        
    }
}

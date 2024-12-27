<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmptiesLogProduct;
use App\Models\EmptiesReceivingLog;
use App\Models\EmptiesReturningLogs;
use App\Models\EmptiesReturningLogsProduct;
use App\Models\EmptiesOnGroundProduct;
use App\Models\EmptiesOnGroundLog;
use App\Events\EmptiesOnGroundSaved;
use App\Events\ReturnProductToGGBL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EmptiesLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $emptiesReceivingLogs = EmptiesReceivingLog::latest('date')->with("products")->get();

        return response()->json([
            "success" => true,
            "data" => $emptiesReceivingLogs
        ]);
    }

    public function getEmptiesReturned() {
        //Get empties returned logs

        $emptiesReturnedLogs = EmptiesReturningLogs::with("products")->get();

        return response()->json([
            "success" => true,
            "data" => $emptiesReturnedLogs
        ]);
    }

    public function postEmptiesReturned(Request $request) {

        $emptiesReturnedLogs = EmptiesReturningLogs::updateOrCreate(
            [
                'date' => $request->get('date'),
                'vehicle_number' => $request->get('vehicle_number'),
            ],
            [
                'returned_by' => $request->get('returned_by'),
                'number_of_pallets' => $request->get('pallets_number'),
                'quantity' => $request->get('quantity')
            ]
        );

        Log::info(["Empty Returned Logs" => $emptiesReturnedLogs]);

        if ($emptiesReturnedLogs) {
            $attributes = json_decode($request->get('products'));

            foreach($attributes as $attrib) {
                EmptiesReturningLogsProduct::updateOrCreate([
                    'empties_returning_log_id' => $emptiesReturnedLogs->id,
                    'product_id' => $attrib->product_id
                ],
                [
                    'quantity' => $attrib->quantity
                ]);
            }
        }

        return response()->json([
            "success" => true,
            "data" => "Empty Log was saved successfully"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Log::info($request->all());
        // Upload an Image File to Cloudinary with One line of Code
        $uploadedFileUrl = Cloudinary::upload($request->file('image_ref')->getRealPath(), [
            'folder' => 'Crate-Empties-Mgt'
        ])->getSecurePath();

        // Access the uploaded image URL

        $emptiesLog = new EmptiesReceivingLog();
        $emptiesLog->date = $request->get('date');
        $emptiesLog->vehicle_number = $request->get('vehicle_number');
        $emptiesLog->number_of_pallets = $request->get('pallets_number');
        $emptiesLog->purchase_order_number = $request->get('purchase_order');
        $emptiesLog->received_by = $request->get('received_by');
        $emptiesLog->delivered_by = $request->get('delievered_by');
        $emptiesLog->quantity_received = $request->get('quantity');
        $emptiesLog->image_reference = $uploadedFileUrl;

        if ($emptiesLog->save()) {
            //save the sub product quantities
            $products = json_decode($request->get('product-quanties'));
            foreach ($products as $product) {
                $emptiesLogProduct = new EmptiesLogProduct();
                $emptiesLogProduct->empties_log_id = $emptiesLog->id;
                $emptiesLogProduct->product_id = $product->product;
                $emptiesLogProduct->quantity = $product->quantity;
                $emptiesLogProduct->save();
            }
        }

        return response()->json([
            "success" => true,
            "data" => "Empty Log was saved successfully"
        ]);
    }

    public function getEmptiesOnGround(Request $request) {

        $emptiesOnGround = EmptiesOnGroundLog::with(["emptiesOnGroundProducts" => function ($query) {
            $query->with("product");
        }])->get();

        return response()->json([
            "success" => true,
            "data" => $emptiesOnGround
        ]);
    }

    public function postEmptiesOnGround(Request $request) {
        $date = $request->get('date');
        $pcs = $request->get('pcs_number');
        $quantity = $request->get('quantity');
        $products = $request->get('products');

        $emptiesOnGround = EmptiesOnGroundLog::updateOrCreate([
            'date' => $date,
        ],
        [
            'quantity' => $quantity,
            'number_of_pcs' => $pcs
        ]);
        Log::info($emptiesOnGround);

        if ($emptiesOnGround) {

            $attributes = json_decode($request->get('empties_on_ground_products'));

            foreach ($attributes as $product) {
                // $emptiesOnGroundProduct = new EmptiesOnGroundProduct;
                EmptiesOnGroundProduct::updateOrCreate([
                    'product_id' => $product->product_id,
                    'date' => $date
                ],
                [
                    'quantity' => $product->quantity,
                    'empties_on_ground_log_id' => $emptiesOnGround->id,
                    'is_empty' => $product->is_empty,
                    'number_of_pcs' => $emptiesOnGround->number_of_pcs
                ]);

                $emptiesBalance = [];
                $emptiesBalance['product_id'] = $product->product_id;
                $emptiesBalance['quantity'] = $product->quantity;

                EmptiesOnGroundSaved::dispatch($emptiesBalance);

            }

            return response()->json([
                "success" => true,
                "data" => "Empty Log was saved successfully"
            ]);

        }else {

            return response()->json([
                "success" => false,
                "data" => "Empty Log was not saved successfully"
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

    public function postEmptiesLoan(Request $request) {

    }

    public function getEmptiesLoan(Request $request) {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        Log:info($request->all());
        $result = EmptiesReceivingLog::where('id', $id)->update($request->all());

        return response()->json([
            "success" => true,
            "data" => "Empty Log was updated successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $result = EmptiesReceivingLog::where('id', $id)->delete();

        return response()->json([
            "success" => true,
            "data" => "Empty Log was deleted successfully"
        ]);
    }
}

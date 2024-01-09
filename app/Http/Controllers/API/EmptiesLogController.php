<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmptiesLogProduct;
use App\Models\EmptiesReceivingLog;
use App\Models\EmptiesReturningLogs;
use App\Models\EmptiesReturningLogsProduct;
use App\Models\EmptiesOnGroundProduct;
use App\Models\EmptiesOnGroundLog;
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
        
        $emptiesReturnedLogs = new EmptiesReturningLogs();
        $emptiesReturnedLogs->date = $request->get('date');
        $emptiesReturnedLogs->vehicle_number = $request->get('vehicle_number');
        $emptiesReturnedLogs->returned_by = $request->get('returned_by');
        $emptiesReturnedLogs->number_of_pallets = $request->get('pallets_number');
        $emptiesReturnedLogs->quantity = $request->get('quantity');

        if ($emptiesReturnedLogs->save()) {
            $attributes = json_decode($request->get('products'));
            
            foreach($attributes as $attrib) {
                $emptiesReturningProductLogs = new EmptiesReturningLogsProduct();
                $emptiesReturningProductLogs->product_id = $attrib->product_id;
                $emptiesReturningProductLogs->quantity = $attrib->quantity;
                $emptiesReturningProductLogs->empties_returning_log_id = $emptiesReturnedLogs->id;
                $emptiesReturningProductLogs->save();

                //update the empties on ground
                self::reduceEmptiesOnGround($attrib->product_id, $attrib->quantity);

            }
        }

        //deduct the empties from the companies account

        return response()->json([
            "success" => true,
            "data" => "Empty Log was saved successfully"
        ]);

    }

    public function reduceEmptiesOnGround($product_id, $quantity) {
        //Reduce the empties on ground
        

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
        Log::info($request->all());

        $date = $request->get('date');
        $pcs = $request->get('pcs_number');
        $quantity = $request->get('quantity');
        $products = $request->get('products');

        $emptiesOnGround = new EmptiesOnGroundLog;
        $emptiesOnGround->date = $date;
        $emptiesOnGround->quantity = $quantity;
        $emptiesOnGround->number_of_pcs = $pcs;

        if ($emptiesOnGround->save()) {

            $attributes = json_decode($request->get('empties_on_ground_products'));
            
            foreach ($attributes as $product) {
                $emptiesOnGroundProduct = new EmptiesOnGroundProduct;
                $emptiesOnGroundProduct->product_id = $product->product_id;
                $emptiesOnGroundProduct->quantity = $product->quantity;
                $emptiesOnGroundProduct->empties_on_ground_log_id = $emptiesOnGround->id;
                $emptiesOnGroundProduct->is_empty = $product->is_empty;
                $emptiesOnGroundProduct->date = $date;
                $emptiesOnGroundProduct->save();
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
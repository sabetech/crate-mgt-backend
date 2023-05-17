<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmptiesReceivingLog;
use App\Models\EmptiesReturningLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmptiesLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $emptiesReceivingLogs = EmptiesReceivingLog::with("products")->get();

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
        Log::info("EVERYKEY::", $request->all());
        Log::info($request->get('products'));

        $emptiesReturnedLogs = new EmptiesReturningLogs();
        $emptiesReturnedLogs->date = $request->get('date');
        $emptiesReturnedLogs->vehicle_number = $request->get('vehicle_number');
        $emptiesReturnedLogs->returned_by = $request->get('returned_by');
        $emptiesReturnedLogs->quantity = $request->get('quantity_returned');

        if ($emptiesReturnedLogs->save()) {
            $attributes = json_decode($request->get('products'), true);
            Log::info("Convert to array::", $attributes);
            $emptiesReturnedLogs->products()->attach($emptiesReturnedLogs->id, json_decode($request->get('products'), true) );
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

        // $emptiesLog = new EmptiesReceivingLog();
        // $emptiesLog->date = $request->get('date');
        // $emptiesLog->vehicleNumber = $request->get('vehicle_number');
        // $emptiesLog->purchase_order_number = $request->get('purchase_order_number');
        // $emptiesLog->received_by = $request->get('received_by');
        // $emptiesLog->delivered_by = $request->get('image_reference');
        // $emptiesLog->quantity = 0;
        // $emptiesLog->save();
        // $("#aliquot_id").on('change', function() {
        //     if ($(this).value().length > 0) {
        //         $("#daterange").attr('disabled', true)
        //     }else{
        //         $("#daterange").attr('disabled', false)
        //     }
            
        // });


        return response()->json([
            "success" => true,
            "data" => "Empty Log was saved successfully"
        ]);
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
}

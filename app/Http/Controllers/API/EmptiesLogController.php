<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmptiesReceivingLog;
use App\Models\EmptiesReturningLogs;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

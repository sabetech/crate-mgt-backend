<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmptiesBalance;
use App\Models\EmptiesTransaction;

class EmptiesController extends Controller
{
    public function getEmptiesBalances(Request $request) {

        $empties = EmptiesBalance::with('product')->get();

        return response()->json([
            'data' => $empties
        ], 200);

    }

    public function getEmptiesTransactions(Request $request) {

        $emptiesTransaction = EmptiesTransaction::with('product')->orderBy('datetime', 'desc')->get();
        return response()->json([
            'data' => $emptiesTransaction
        ], 200);
    }
}

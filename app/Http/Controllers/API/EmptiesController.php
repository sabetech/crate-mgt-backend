<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmptiesBalance;
use App\Models\EmptiesTransaction;
use App\Models\CustomerEmptiesAccount;

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

    public function getEmptiesInTrade(Request $request) {
        //improve this method of getting empties in trade later ... when the database becomes, it may become impractical to use this method
        $emptiesInTrade = CustomerEmptiesAccount::get();

        $emptieInTradeCount = $emptiesInTrade->reduce(function($carry, $item) {
            if ($item->transaction_type === 'out' ){
                return $carry + $item->quantity_transacted;
            }else{
                return $carry - $item->quantity_transacted;
            }
        }, 0);

        return response()->json([
            'data' => $emptieInTradeCount
        ]);
    }
}

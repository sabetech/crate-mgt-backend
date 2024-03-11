<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmptiesBalance;

class EmptiesController extends Controller
{
    public function getEmptiesBalances(Request $request) {

        $empties = EmptiesBalance::with('product')->get();

        return response()->json([
            'data' => $empties
        ], 200);

    }
}

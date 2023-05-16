<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "v1"], function () {
    Route::apiResource("empties-receiving-logs", \App\Http\Controllers\API\EmptiesLogController::class);
    Route::get("empties-returned-logs", [\App\Http\Controllers\API\EmptiesLogController::class, 'getEmptiesReturned']);
    Route::apiResource("products_returnable", \App\Http\Controllers\API\ProductController::class);
});
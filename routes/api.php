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
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/health', function() {
        return response()->json([
            "success" => true,
            "data" => "[GET] Server is up and running"
        ]);
    });

    Route::post('/health', function() {
        return response()->json([
            "success" => true,
            "data" => "[POST] Server is up and running"
        ]);
    });

    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::apiResource("empties-receiving-logs", \App\Http\Controllers\API\EmptiesLogController::class);
        Route::get("empties-returned-logs", [\App\Http\Controllers\API\EmptiesLogController::class, 'getEmptiesReturned']);
        Route::post("empties-returned-logs", [\App\Http\Controllers\API\EmptiesLogController::class, 'postEmptiesReturned']);
        Route::post("empties-onground-log", [\App\Http\Controllers\API\EmptiesLogController::class, 'postEmptiesOnGround']);
        Route::get("empties-onground-log", [\App\Http\Controllers\API\EmptiesLogController::class, 'getEmptiesOnGround']);

        Route::apiResource("products_returnable", \App\Http\Controllers\API\ProductController::class);
        Route::apiResource("products", \App\Http\Controllers\API\ProductController::class);

        Route::apiResource("customers", \App\Http\Controllers\API\CustomerController::class);
        Route::post("customer_empties_returns", [\App\Http\Controllers\API\CustomerController::class, 'postReturnEmpties']);

        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});

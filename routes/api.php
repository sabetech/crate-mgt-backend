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
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
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
        
        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);


        Route::apiResource("empties-receiving-logs", \App\Http\Controllers\API\EmptiesLogController::class);
        Route::get("empties-returned-logs", [\App\Http\Controllers\API\EmptiesLogController::class, 'getEmptiesReturned']);
        Route::post("empties-returned-logs", [\App\Http\Controllers\API\EmptiesLogController::class, 'postEmptiesReturned']);
        Route::post("empties-onground-log", [\App\Http\Controllers\API\EmptiesLogController::class, 'postEmptiesOnGround']);
        Route::get("empties-onground-log", [\App\Http\Controllers\API\EmptiesLogController::class, 'getEmptiesOnGround']);

        Route::post("empties-loan", [\App\Http\Controllers\API\EmptiesLogController::class, 'postEmptiesLoan']);
        Route::get("empties-loan", [\App\Http\Controllers\API\EmptiesLogController::class, 'getEmptiesLoan']);

        Route::apiResource("products_returnable", \App\Http\Controllers\API\ProductController::class);
        Route::get("products-all", [\App\Http\Controllers\API\ProductController::class, 'getAllProducts']);

        

        Route::group(["prefix" => "products"], function () {
            Route::get('balance', [\App\Http\Controllers\API\ProductController::class, 'getProductStockBalance']);
            Route::put('/edit/{id}', [\App\Http\Controllers\API\ProductController::class, 'modifyProduct']);
            Route::post('/new/{id}', [\App\Http\Controllers\API\ProductController::class, 'modifyProduct']);
        });

        Route::apiResource("customers", \App\Http\Controllers\API\CustomerController::class);
        Route::post("customer_empties_returns", [\App\Http\Controllers\API\CustomerController::class, 'postReturnEmpties']);
        Route::get("customer_history/{id}", [\App\Http\Controllers\API\CustomerController::class, 'getCustomerHistory']);

        Route::get("vse_loadout_info/{id}", [\App\Http\Controllers\API\CustomerController::class, 'getLoadoutInfoByVse']);
        Route::post("record_vse_sales/{id}", [\App\Http\Controllers\API\CustomerController::class, 'postRecordVseSales']);

        Route::apiResource("users", \App\Http\Controllers\API\UserController::class);
        Route::get("roles", [\App\Http\Controllers\API\UserController::class, 'getRoles']);

        Route::group(["prefix" => "stocks"], function () {
            Route::post("take-stock", [\App\Http\Controllers\API\ProductController::class, 'takeStock']);
            Route::get("get-stock", [\App\Http\Controllers\API\ProductController::class, 'getStock']);
            Route::post("receivable", [\App\Http\Controllers\API\ProductController::class, 'addReceivable']);
            Route::post("returns-from-vse", [\App\Http\Controllers\API\ProductController::class, 'returnsFromVse']);
            Route::post('loadout', [\App\Http\Controllers\API\ProductController::class, 'postLoadout']);
            Route::get('loadouts', [\App\Http\Controllers\API\ProductController::class, 'getLoadoutProducts']);
            Route::get('loadout-by-vse', [\App\Http\Controllers\API\ProductController::class, 'getLoadoutByVses']);
            Route::get('pending-orders', [\App\Http\Controllers\API\ProductController::class, 'getPendingOrders']);
            Route::post('approve-order/{inventoryOrder}', [\App\Http\Controllers\API\ProductController::class, 'approveOrder']);
        });

        Route::group(["prefix" => "sales"], function () {
            Route::post("pay", [\App\Http\Controllers\API\SalesController::class, 'pay']);
            Route::get("/", [\App\Http\Controllers\API\SalesController::class, 'sales']);
        });

    });
});

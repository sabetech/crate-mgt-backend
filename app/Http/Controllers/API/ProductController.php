<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::where('empty_returnable', 1)->get();

        return response()->json([
            "success" => true,
            "data" => $products
        ]);
    }

    /**
     * Get all products.
     */
    public function getAllProducts() {
        $products = Product::all();

        return response()->json([
            "success" => true,
            "data" => $products
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

    public function takeStock(Request $request){
        $date = $request->get('date');
        $products = $request->get('products');
        $breakages = $request->get('breakages');

        $user = Auth::user();

        $allProducts = Products::all();

        $countedProdutsArray = [];
        foreach ($products as $product) {
            $countedProdutsArray[$product['id']] = $product['quantity'];
        }
        $breakagesQtyArray = [];
        foreach ($breakages as $breakage) {
            $breakagesQtyArray[$breakage['id']] = $breakage['quantity'];
        }

        foreach($allProducts as $opkProducts) {
            
            if (array_key_exists($opkProducts->id, $countedProdutsArray) ||
                array_key_exists($opkProducts->id, $breakagesQtyArray)) {
                
                $stockItem = Stock::updateOrCreate(
                    [
                        'date' => $date,
                        'product_id' => $opkProducts->id
                    ],
                    [
                        'quantity' => $countedProdutsArray[$opkProducts->id] ?? 0,
                        'breakages' => $breakagesQtyArray[$opkProducts->id] ?? 0,
                        'user_id' => $user->id
                    ]
                );
            }
        }

        return response()->json([
            "success" => true,
            "data" => "Stocks taken successfully"
        ]);
    }

    public function getProductsAndCurrentBalances() {
        
    }
}

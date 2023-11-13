<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\LoadoutProduct;
use App\Models\Customer;
use App\Models\InventoryOrder;
use Carbon\Carbon;

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

    public function getStock(Request $request) {
        $date = $request->get('date');
        $stocks = Stock::where('date', $date)->with('product')->get();

        return response()->json([
            "success" => true,
            "data" => $stocks
        ]);
    }

    public function takeStock(Request $request){
        $date = $request->get('date');
        $products = $request->get('products');
        $breakages = $request->get('breakages');
        $carbonDate = Carbon::createFromFormat('D, d M Y H:i:s e', $date);
        $date = $carbonDate->format('Y-m-d');
        $user = Auth::user();

        $allProducts = Product::all();

        $products = json_decode($products, true);
        $breakages = json_decode($breakages, true);

        $countedProdutsArray = [];
        if ($products == null) {
            $products = [];
        }
        foreach ($products as $product) {
            $countedProdutsArray[$product['product']] = $product['quantity'];
        }
        
        $breakagesQtyArray = [];
        if ($breakages == null) {
            $breakages = [];
        }
        foreach ($breakages as $breakage) {
            $breakagesQtyArray[$breakage['product']] = $breakage['quantity'];
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

    public function getProductStockBalance() {
        $products = Product::with(['stocks' => 
            function ($query) {
                $query->latest('date');
           }])->get();

        return response()->json([
            "success" => true,
            "data" => $products
        ]);
    }

    public function getLoadoutProducts(Request $request) {
        $date = $request->get('date');
        $loadout = LoadoutProduct::where('date', $date)->with(['product', 'customer'])->get();

        return response()->json([
            "success" => true,
            "data" => $loadout
        ]);
    }

    public function getLoadoutByVse(Request $request) {
        $date = $request->get('date');

        $VSEs = Customer::where('customer_type', 'retailer-vse')->with(['vseLoadout' => 
            function ($query) use ($date) {
                $query->where('date', $date)
                    ->with(['product']);
            }])->get();

        return response()->json([
            "success" => true,
            "data" => $VSEs
        ]);
    }

    public function postLoadout(Request $request) {
        $date = $request->get('date');
        $products = $request->get('products');
        $customer = $request->get('vse');
        $user = Auth::user();

        $products = json_decode($products, true);

        foreach ($products as $product) {
            $loadout = new LoadoutProduct;
            $loadout->date = $date;
            $loadout->customer_id = $customer;
            $loadout->product_id = $product['product'];
            $loadout->quantity = $product['quantity'];
            $loadout->user_id = $user->id;
            $loadout->save();
        }

        return response()->json([
            "success" => true,
            "data" => "Loadout saved successfully"
        ]);
    }

    public function getPendingOrders() {
        $pendingOrders = InventoryOrder::where('status', 'pending')->with(['order' => function($query) {
            $query->with(['customer', 'sales' => function($query) {
                $query->with('product');
            }]);
        }])->get();

        return response()->json([
            "success" => true,
            "data" => $pendingOrders
        ]);
    }
}

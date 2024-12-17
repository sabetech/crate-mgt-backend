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
use App\Models\InventoryReceivable;
use App\Models\InventoryTransaction;
use App\Models\InventoryBalance;
use App\Events\InventoryOrderApproved;
use Carbon\Carbon;
use App\Events\InventoryReceivedFromGBL;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;

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

    public function receivableLogs(Request $request) {
        $date = $request->get('date');

        if (!$date) {
            $date = date("Y-m-d");
        }

        $receivableLogs = InventoryReceivable::getReceivableLog($date);

        return response()->json([
            "success" => true,
            "data" => $receivableLogs
        ]);
    }

    public function inventoryHistory(Request $request) {
        $date = $request->get("date");

        if (!$date) {
            $date = date("Y-m-d");
        }

        $inventoryTransactions = InventoryTransaction::getInventoryByDate($date);
        return response()->json([
            "success" => true,
            "data" => $inventoryTransactions
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

    public function takeStock(Request $request) {

        //update product balances as you take stock

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
        $products = Product::with(['inventoryBalance'])->get();

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

    public function getLoadoutByVses(Request $request) {
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

        //[validate] check if that many products are actually there ...
        $productValidationErrors = [];

        foreach ($products as $product) {
            $product_id = $product['product'];
            $quantity = $product['quantity'];

            $balance = InventoryBalance::where('product_id', $product_id)->first();

            Log::info($balance);

            if (!$balance || $balance->quantity < $quantity) {
                Log::info("No or low Balance");
                $productValidationErrors[] = [
                    'product' => Product::find($product_id),
                    'quantity' => $balance ? $balance->quantity : 0,
                    'required' => $quantity
                ];
            }
        }

        if (count($productValidationErrors) > 0) {
            return response()->json([
                "success" => false,
                "data" => $productValidationErrors,
                "message" => "Not enough products in inventory"
            ], 422);
        }

        foreach ($products as $product) {

            LoadoutProduct::updateOrCreate(
                [
                    'date' => $date,
                    'customer_id' => $customer,
                    'product_id' => $product['product'],
                ],
                [
                    'quantity' => $product['quantity'],
                    'user_id' => $user->id
                ]
            );
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

    public function approveOrder(InventoryOrder $inventoryOrder) {

        $inventoryOrder->status = 'approved';
        $inventoryOrder->save();

        InventoryOrderApproved::dispatch($inventoryOrder);

        return response()->json([
            "success" => true,
            "data" => "Order approved successfully"
        ]);
    }

    public function addReceivable(Request $request) {
        $date = $request->get('date');
        $products = $request->get('products');

        if ($products === 'undefined') return response()->json([
            "success" => false,
            "message" => "No Products were selected. Select the products"
        ], 400);

        $purchaseOrderId = $request->get('purchase_order_id');
        $user = Auth::user();

        $products = json_decode($products, true);
        Log::info($request->all());

        $image = $request->file('image_ref');
        $deployedEnv = config('deployment.environment');

        $image_path = "";

        if ($deployedEnv == 'On_Prem') {
            $imageName = time().'.'.$image->extension();
            // Generate a unique filename
            // $image_path = Storage::disk('local')->put('images', $image, time() . '.' . $image->getClientOriginalExtension());
            $image->move(public_path('images'), $imageName);
            $image_path = "/images/" . $imageName;
        }else {
            $image_path = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'Crate-Empties-Mgt'
            ])->getSecurePath();
        }

        Log::info($image_path);

        foreach ($products as $product) {
            InventoryReceivable::updateOrCreate([
                'date' => $date,
                'purchase_order_number' => $purchaseOrderId,
                'product_id' => $product['product'],
            ],
            [
                'quantity' => $product['quantity'],
                'way_bill_image_url' => $image_path,
                'user_id' => $user->id
            ]);
        }

        $dataToDispatch = $request->all();
        $dataToDispatch = [...$dataToDispatch, 'imageUrl' => $image_path];

        InventoryReceivedFromGBL::dispatch($dataToDispatch);


        return response()->json([
            "success" => true,
            "data" => "Inventory Receivable saved successfully"
        ]);
    }

    public function returnsFromVse(Request $request) {
        $date = $request->get('date');
        $products = $request->get('products');
        $vse = $request->get('vse');
        $user = Auth::user();

        $products = json_decode($products, true);

        foreach ($products as $product) {
            //update inventory balance
            $loadoutProduct = LoadoutProduct::where('date', $date)
                ->where('customer_id', $vse)
                ->where('product_id', $product['product'])
                ->first();

            if ($loadoutProduct) {
                $loadoutProduct->returned = $product['quantity'];
                $loadoutProduct->quantity_sold = $loadoutProduct->quantity - $loadoutProduct->returned;
                $loadoutProduct->vse_outstandingbalance = $loadoutProduct->quantity - ($loadoutProduct->returned + $loadoutProduct->quantity_sold);
                $loadoutProduct->save();
            }
        }

        return response()->json([
            "success" => true,
            "data" => "Returns from VSE saved successfully"
        ]);

    }

    public function modifyProduct(Request $request, $id) {
        $product = Product::find($id);

        if (!$product) return response()->json([
            "success" => false,
            "data" => "Product not found"
        ]);

        $product->sku_name = $request->get('sku_name');
        $product->sku_code = $request->get('sku_code');
        $product->retail_price = $request->get('retail_price');
        $product->wholesale_price = $request->get('wholesale_price');
        $product->save();

        return response()->json([
            "success" => true,
            "data" => "Product updated successfully"
        ]);
    }

}

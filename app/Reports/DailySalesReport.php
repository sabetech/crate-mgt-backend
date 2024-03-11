<?php
namespace App\Reports;

use DB;
use App\Models\Sale;

class DailySalesReport {

    protected $from, $to, $customerOption;
    public function __construct($from, $to, $customerOption ) {
        $this->from = $from;
        $this->to = $to;
        $this->customerOption = $customerOption;
    }

    public function generate(){
        $sales = Sale::join('orders', 'orders.id', 'sales.order_id')
                        ->join('products', 'products.id', 'sales.product_id')
                        ->join('customers', 'customers.id', 'orders.customer_id')
        ->whereBetween('orders.date', [$this->from, $this->to])
        ->where('customer_type', $this->customerOption);

        return $sales;
    }

    public function aggregateSalesByDateAndProduct($salesBuilder){

        return $salesBuilder->groupBy('orders.date', 'products.id', 'sales.unit_price')
        ->select(
            'orders.date',
            'products.sku_name',
            DB::raw("SUM(sales.quantity) as Qty_Sold"),
            'sales.unit_price',
            DB::raw("SUM(sales.sub_total) as total_sales")
        );
    }
}

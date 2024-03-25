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
                        ->join('users', 'users.id', 'sales.user_id')
        ->whereBetween('orders.date', [$this->from, $this->to])
        ->where('customer_type', $this->customerOption)
        ->where('orders.status', 'approved')
        ->orderBy('customers.name')
        ->select(
            'sales.*',
            'products.sku_name',
            'customers.id as customer_id',
            'customers.name as customer_name',
            'customers.customer_type',
            'customers.phone as customer_phone',
            'orders.amount_tendered',
            'orders.updated_at as transaction_date',
            'orders.payment_type',
            'orders.date as order_date',
            'orders.transaction_id',
            'users.name'
        );

        return $sales;
    }

    public function aggregateSalesByDateAndProduct($salesBuilder){

        return $salesBuilder->groupBy('orders.date', 'products.id', 'sales.unit_price')
        ->select(
            'orders.date',
            'products.sku_name',
            'sales.quantity as Qty_Sold',
            'sales.unit_price',
            'sales.sub_total as total_sales'
        );
    }
}

<!DOCTYPE html>
<html>
    <head>
        <title>{{ $title }}</title>
        <style>
            /* Your PDF styling here */
            .main {
                margin: 1em
            },
            table {
               border-collapse: collapse; /* Ensures borders between cells */
            }
            th, td {
                border: 1px solid #ddd; /* Define 1px solid gray border for all cells */
                padding: 5px; /* Add padding for readability */
            }
            .table-header {
                background-color: #D3D3D3
            }
            .table {
                border-color: #D3D3D3
                border-radius: 5px
                border-width: 1px
            }
            .row {
                margin-bottom: 10px
                border-width: 1px
            }
            .trans_date {
                text-align: center,
                width: '15%'
            }
            .product_name {
                text-align: center,
                width: '20%'
            }
            .qty_sold {
                text-align: center
            }
            .customer {
                background-color: #ebeaea
            }

        </style>
    </head>
    <body>
        <div class="main">
            <h3 style="text-align: center">{{ $title }}</h3>

            <table >
                <thead class="table-header">
                    <th >
                        Transaction Date
                    </th>
                    <th >
                        Product Name
                    </th>
                    <th >
                        Product Cost
                    </th>
                    <th>
                        Quantity
                    </th>
                    <th>
                        Total
                    </th>
                    <th>
                        Transaction ID
                    </th>
                    <th>
                        Sale By
                    </th>
                </thead>
                <tbody>
                    <?php
                        $currentCustomer = $sales[0]->customer_id;
                        $transactionID = $sales[0]->transaction_id;
                        $customerSum = 0;
                        $customerTotalSale = 0;
                        $customerAmountTendered = 0;
                        $countPurchases = 0;
                    ?>
                    <tr class="customer">
                        <td colspan="8">
                            Customer: {{ $sales[0]->customer_name }}
                        </td>
                    </tr>
                    @foreach($sales as $key => $sale)
                        @if ($sale->transaction_id != $transactionID)
                            $customerAmountTendered += $sale->amount_tendered;
                        @endif
                        @if ($sale->customer_id != $currentCustomer)
                            <?php $currentCustomer = $sale->customer_id ?>
                            <tr class="table-header">
                                {{-- Calculate the totals here --}}
                                <td colspan="1"></td>
                                <td >
                                    {{-- Total Payment Made: {{ $customerAmountTendered }} --}}
                                </td>
                                <td >
                                    Number of purchases: {{ $countPurchases }}
                                </td>

                                <td>
                                    Qty
                                    <strong>{{ $customerSum }}</strong>
                                    <?php $customerSum = 0 ?>
                                </td>
                                <td >
                                    <strong>{{ $customerTotalSale }}</strong>
                                </td>
                                <td colspan="2"  style="text-align: right;">
                                </td>
                            </tr>
                            <tr class="customer">
                                <td colspan="8">
                                    Customer: {{ $sale->customer_name }}
                                </td>
                            </tr>
                        @endif
                       <tr>
                            <td>
                                {{ date('d/m/Y H:i:s', strtotime($sale->transaction_date))  }}
                            </td>
                            <td>
                                <?php $countPurchases++; ?>
                                {{$sale->sku_name  }}
                            </td>
                            <td>
                                {{$sale->unit_price  }}
                            </td>
                            <td>
                                <?php $customerSum += $sale->quantity; ?>
                                {{$sale->quantity  }}
                            </td>
                            <td>
                                {{ $sale->sub_total  }}
                                <?php $customerTotalSale += $sale->sub_total; ?>
                            </td>
                            <td>
                                {{ $sale->transaction_id }}
                            </td>
                            <td>
                                {{ $sale->name }}
                            </td>
                       </tr>
                    @endforeach
                    <tr class="customer">
                        {{-- Calculate the totals here --}}
                        <td colspan="1"></td>
                        <td >
                            Total Payment Made: {{ $customerAmountTendered }}
                        </td>
                        <td >
                            Number of purchases: {{ $countPurchases }}
                        </td>

                        <td>
                            Qty
                            <strong>{{ $customerSum }}</strong>
                            <?php $customerSum = 0 ?>
                        </td>
                        <td >
                            <strong>{{ $customerTotalSale }}</strong>
                        </td>
                        <td colspan="2"  style="text-align: right;">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>

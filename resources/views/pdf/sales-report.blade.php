<!DOCTYPE html>
<html>
    <head>
        <title>{{ $title }}</title>
        <style>
            /* Your PDF styling here */
            .main {
                margin: 2em
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

        </style>
    </head>
    <body>
        <div class="main">
            <h3 style="text-align: center">{{ $title }}</h3>

            <table >
                <thead>
                    <th >
                        Trans Date
                    </th>
                    <th >
                        Product Name
                    </th>
                    <th >
                        Qty Sold
                    </th>
                    <th>
                        Retail Price
                    </th>
                    <th>
                        Total Price
                    </th>
                </thead>
                <tbody>
                    @foreach($aggregatedSales as $aggSale)
                        <tr class="row">
                            <td class="trans_date">
                                {{ $aggSale->date }}
                            </td>
                            <td>
                                {{ $aggSale->sku_name }}
                            </td>
                            <td class="qty_sold">
                                {{ $aggSale->Qty_Sold }}
                            </td>
                            <td>
                                {{ $aggSale->unit_price }}
                            </td>
                            <td>
                                {{ $aggSale->total_sales }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>SUM: {{ array_sum( array_map(function($aggSale) {
                            return $aggSale['Qty_Sold'];
                        }, $aggregatedSales->toArray() ) ) }}</td><td></td>
                        <td>SUM:
                            {{ array_sum( array_map(function($aggSale) {
                                return $aggSale['total_sales'];
                            }, $aggregatedSales->toArray(),  )) }}
                        </td>
                </tr>
            </tfoot>
            </table>
        </div>
    </body>
</html>

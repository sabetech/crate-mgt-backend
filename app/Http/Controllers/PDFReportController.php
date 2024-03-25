<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Reports\DailySalesReport;
use function Spatie\LaravelPdf\Support\pdf;

class PDFReportController extends Controller
{
    //
    public function generateReportPDF (Request $request) {

        $from = $request->get('from');
        $to = $request->get('to');

        $customerOption = $request->get('customerOption');
        $dailySalesReport = new DailySalesReport($from, $to, $customerOption);

        $salesBuilder = $dailySalesReport->generate();
        $aggregatedSales = $dailySalesReport->aggregateSalesByDateAndProduct($salesBuilder)->get();

        $data = [  // Data to be passed to the PDF view
            'title' => 'Details of Goods Sold: ' . date("d/m/Y", strtotime($from)) .' - ' . date("d/m/Y", strtotime($to)),
            'aggregatedSales' => $aggregatedSales
        ];

        $pdf = PDF::loadView('pdf.sales-report', $data);

        return $pdf->stream('document.pdf');
    }

}

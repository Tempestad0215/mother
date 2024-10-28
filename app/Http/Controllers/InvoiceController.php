<?php

namespace App\Http\Controllers;

use App\Invoices\SaleInvoiceA;
use App\Models\Sale;

class InvoiceController extends Controller
{
    public function getA(Sale $sale)
    {
        //Crear la nueva factura
        $pdf = new SaleInvoiceA($sale);

        //Devolver el PDF como un base 64
        $pdfString = base64_encode($pdf->setData());

        //Devolve como un json para mejor manejo
        return response()->json($pdfString);
    }
}

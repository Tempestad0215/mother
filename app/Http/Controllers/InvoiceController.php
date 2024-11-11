<?php

namespace App\Http\Controllers;

use App\Invoices\SaleInvoiceA;
use App\Models\Sale;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function getA(int $sale)
    {
        //Buscar la venta
        $sale = Sale::find($sale);
        //Verificar si no existe la venta
        if (!$sale)
        {
            return response()->json(['error' => 'Venta No Encontrada'],404);
        }

        //Crear la ruta y el archivo
//        $pdfFilePath = storage_path('app/public/pdfs/invoice_temp.pdf');

        //Eliminar el PDF si existe
        if (Storage::disk('pdfs')->exists('invoice_temp.pdf'))
        {
            //Eliminar el Pdf existente
            Storage::disk('pdfs')->delete('invoice_temp.pdf');
        }

        //Crear la nueva factura
        $pdf = new SaleInvoiceA($sale);
        $pdfContent = $pdf->setData();

        //Guadar e pdfo
        Storage::disk('pdfs')->put('invoice_temp.pdf', $pdfContent);

        //Genearr la url
        $url = config('app.url').'/storage/pdfs/invoice_temp.pdf';


        //Devolve como un json para mejor manejo
        return response()->json([
            'msj' => 'PDF generado y guardado temporalmente',
            'url' => $url,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Invoices\InvoiceCounterB5;
use App\Invoices\SaleInvoiceA;
use App\Models\MoneyCounter;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
//    public function getA(int $sale)
//    {
//        //Buscar la venta
//        $sale = Sale::find($sale);
//        //Verificar si no existe la venta
//        if (!$sale)
//        {
//            return response()->json(['error' => 'Venta No Encontrada'],404);
//        }
//
//        //Crear la ruta y el archivo
////        $pdfFilePath = storage_path('app/public/pdfs/invoice_temp.pdf');
//
//        //Eliminar el PDF si existe
//        if (Storage::disk('pdfs')->exists('invoice_temp.pdf'))
//        {
//            //Eliminar el Pdf existente
//            Storage::disk('pdfs')->delete('invoice_temp.pdf');
//        }
//
//        //Crear la nueva factura
//        $pdf = new SaleInvoiceA($sale);
//        $pdfContent = $pdf->setData();
//
//        //Guadar e pdfo
//        Storage::disk('pdfs')->put('invoice_temp.pdf', $pdfContent);
//
//        //Genearr la url
//        $url = config('app.url').'/storage/pdfs/invoice_temp.pdf';
//
//
//        //Devolve como un json para mejor manejo
//        return response()->json([
//            'msj' => 'PDF generado y guardado temporalmente',
//            'url' => $url,
//        ]);
//    }


//    public function getB(int $counter)
//    {
//        try {
//            //Buscar la venta
//            $counterData = MoneyCounter::find($counter);
//            //Verificar si no existe la venta
//            if (!$counterData)
//            {
//                return response()->json(['error' => 'Reporte No Encontrado'],404);
//            }
//
//            //Crear la ruta y el archivo
////        $pdfFilePath = storage_path('app/public/pdfs/invoice_temp.pdf');
//
//            //Eliminar el PDF si existe
//            if (Storage::disk('pdfs')->exists('invoice_tempb5.pdf'))
//            {
//                //Eliminar el Pdf existente
//                Storage::disk('pdfs')->delete('invoice_tempb5.pdf');
//            }
//
//            //Crear la nueva factura
//            $pdf = new InvoiceCounterB5($counterData->id);
//            $pdfContent = $pdf->setData();
//
//            //Guadar e pdfo
//            Storage::disk('pdfs')->put('invoice_tempb5.pdf', $pdfContent);
//
//            //Genearr la url
//            $url = config('app.url').'/storage/pdfs/invoice_tempb5.pdf';
//
//
//            //Devolve como un json para mejor manejo
//            return response()->json([
//                'msj' => 'PDF generado y guardado temporalmente',
//                'url' => $url,
//            ]);
//        }catch (Exception $e){
//            //Devolve como un json para mejor manejo
//            return response()->json([
//                'Error' => 'Error En Esta Peticion',
//                'Info' => $e,
//            ],400);
//        }
//
//    }


    /**
     * @param Sale $sale
     * @return void
     */
    public function getA(Sale $sale):void
    {
        //Instancia del pdf
        $pdf = new SaleInvoiceA($sale);

        //llamar el pdf seleccionado
        $pdf->getPDFData();
        $pdf->Output('invoice.pdf');
    }


    /**
     * Para obtener los reportes de conteo
     * @param MoneyCounter $counter
     * @return JsonResponse
     */
    public function getB(MoneyCounter $counter): JsonResponse
    {
        return $this->generatePDF(
            $counter,
            InvoiceCounterB5::class,
            'invoice-tempb5.pdf'
        );
    }


    /**
     * Generar el PDF
     * @param Model $model
     * @param string $pdfClass
     * @param string $fileName
     * @return JsonResponse
     */
    public function generatePDF (
        Model $model,
        string $pdfClass,
        string $fileName
    ){
        try {
            //Buscar el registro existente
//            $record = $modelClass::find($id);

            // Verificar si no existe el registro.

            // Eliminar el PDF si ya existe.
            if (Storage::disk('pdfs')->exists($fileName)) {
                Storage::disk('pdfs')->delete($fileName);
            }

            // Crear el PDF.
            $pdf = new $pdfClass($model);
            $pdfContent = $pdf->setData();

            // Guardar el PDF en el almacenamiento.
            Storage::disk('pdfs')->put($fileName, $pdfContent);

            // Generar la URL del archivo.
            $url = config('app.url') . '/storage/pdfs/' . $fileName;


            // Devolver respuesta JSON con la URL.
            return response()->json([
                'msj' => 'PDF Generado y Guardado Temporalmente',
                'url' => $url,
            ]);

        //Verificar si se puede generar el pdf
        }catch (\Exception $e){


            return response()->json([
                'Error' => 'No Es Posible Generar PDF',
                'Info' => 'Error'.$e->getMessage(),
            ]);
        }


    }

}

<?php

namespace App\Http\Controllers;

use App\Invoices\InvoiceCounterB5;
use App\Invoices\SaleInvoiceA;
use App\Models\MoneyCounter;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS2D;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoiceController extends Controller
{

    /**
     * @param Sale $sale
     * @return void
     */
    public function getA(Sale $sale):void
    {
        //Instancia del pdf
        $pdf = new SaleInvoiceA($sale);

        //llamar el pdf seleccionado
        $pdf->setData();
        $pdf->Output('invoice.pdf');
    }



    public function beltSale(Sale $sale)
    {

        //convertir a string
//        $template = view('pdfs.test',[
//            'setting' => Setting::first(),
//            'sale' => $sale
//        ])->render();

        $partUuid = collect(explode('-', $sale->uuid));



        return Pdf::view('pdfs.test',[
            'setting' => Setting::first(),
            'sale' => $sale,
            'partUuid' => $partUuid->first(),
        ])->margins(2,2,2,2)
            ->paperSize(80, 295)
            ->name('test.pdf');


//        Browsershot::html($template)
//            ->paperSize(80,295)
//            ->margins(2,2,2,2)
//            ->savePdf(storage_path('/app/public/pdfs/test.pdf'));
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

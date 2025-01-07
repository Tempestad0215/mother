<?php

namespace App\Http\Controllers;

use App\Enums\ProductTransType;
use App\Enums\ProductTypeEnum;
use App\Invoices\InvoiceCounterB5;
use App\Invoices\SaleInvoiceA;
use App\Models\CreditNote;
use App\Models\MoneyCounter;
use App\Models\ProTrans;
use App\Models\Sale;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Integer;
use Spatie\LaravelPdf\Facades\Pdf;
use function PHPUnit\Framework\isNull;

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
        //Para aumentar la altura de la pagina
        $height = 160;

        $infoData = collect($sale->infoSale->where('type', ProductTransType::VENTAS));

        //Para recorrer los datos
        $infoData->each(function () use (&$height) {
            // Para aumentar el size del PDF
            $height += 15;
        });


        // Devolvemos el pdf para impresion
        return Pdf::view('pdfs.belt.first',[
            'setting' => Setting::first(),
            'sale' => $sale,
            'datePrint' => Carbon::now()->format('d/m/y H:i:s')
        ])->margins(2,2,2,2)
            ->paperSize(80, $height)
            ->name('test.pdf');

    }


    public function beltNote(CreditNote $creditNote)
    {

        //Para aumentar la altura de la pagina
        $height = 140;

        //Para manejar de forma sencilla los datos
        $infoSale = collect($creditNote->trans);

        //Incrementar el tamaño
        $infoSale->each(function () use (&$height) {
            $height += 13;
        });

        return Pdf::view('pdfs.belt.CreditNote',[
            'setting' => Setting::first(),
            'creditNote' => $creditNote,
            'datePrint' => Carbon::now()->format('d/m/y H:i:s')
        ])->paperSize(80, $height)
            ->margins(2,2,2,2);
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

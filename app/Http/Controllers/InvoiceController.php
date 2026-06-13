<?php

namespace App\Http\Controllers;

use App\Invoices\SaleInvoiceA;
use App\Invoices\Ticket80;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PDF;
use Picqer\Barcode\BarcodeGeneratorPNG;

class InvoiceController extends Controller
{

    protected string $pdfGeneratorUrl;

    public function __construct()
    {
        $this->pdfGeneratorUrl = config('appconfig.url_pdf');
    }

    /**
     * @param Sale $sale
     * @return void
     */
    public function getA(Sale $sale): void
    {
        //Instancia del pdf
        $pdf = new SaleInvoiceA($sale);

        //llamar el pdf seleccionado
        $pdf->setData();
        $pdf->Output('invoice.pdf');
    }


    /**
     * @param Sale $sale
     * @return ResponseFactory|JsonResponse|Response
     * @throws ConnectionException
     */
    public function getSaleInvoice(Sale $sale)
    {
        $templateData = View('pdfs.sale.cinta', [
            'sale' => $sale,
            'setting' => Setting::first()
        ])->render();

        // Crear la respuestas
        return $this->facturaCinta($templateData);

    }


    /**
     * @param CreditNote $creditNote
     * @return ResponseFactory|JsonResponse|Response
     * @throws ConnectionException
     */
    public function getCreditNoteInvoice(CreditNote $creditNote)
    {

        // Obtener los items de la nota de credito
        $creditNote->with('items.product');

        // Obtener los datos de la nota de credito
        $template = View('pdfs.credit_note.cinta', [
            'cr' => $creditNote,
            'setting' => Setting::first()
        ])->render();


        // Crear la respuestas
        return $this->facturaCinta($template);

    }


    public function beltSale(Sale $sale)
    {
        //Para aumentar la altura de la pagina
        $pdf = new Ticket80($sale);

        return $pdf->output('invoice.pdf');
    }

    public function beltNote(CreditNote $creditNote)
    {
        //Para aumentar la altura de la pagina
        $pdf = new Ticket80($creditNote);

        return $pdf->output('invoice.pdf');
    }


    /**
     * @throws \Throwable
     */
    public function label(Product $product)
    {
        $start = microtime(true);

        $code = $product->bar_code ?: $product->code;
        $generator = new BarcodeGeneratorPNG();
        $barCode = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128, 2, 55));
        $barcodeUrl = "data:image/png;base64," . $barCode;

        $pdf = PDF::loadView('pdfs.ticket.zebra', [
            'name' => 'Repuesto Camboya',
            'ref' => $product->sku,
            'code_bar' => $barcodeUrl
        ]);
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('page-width', '60mm');
        $pdf->setOption('page-height', '30mm');
        $pdf->setOption('margin-top', '0.2mm');
        $pdf->setOption('margin-left', '0.2mm');
        $pdf->setOption('margin-right', '0.2mm');

        $end = microtime(true);
        $report = $end - $start;
        Log::info('el timpo transcurrido es ' . $report);

        return $pdf->inline('ticket.pdf');
    }


//    public function beltNote(CreditNote $creditNote)
//    {
//
//        //Para aumentar la altura de la pagina
//        $height = 140;
//
//        //Para manejar de forma sencilla los datos
//        $infoSale = collect($creditNote->trans);
//
//        //Incrementar el tamaño
//        $infoSale->each(function () use (&$height) {
//            $height += 13;
//        });
//
//        return Pdf::view('pdfs.belt.CreditNote',[
//            'setting' => Setting::first(),
//            'creditNote' => $creditNote,
//            'datePrint' => Carbon::now()->format('d/m/y H:i:s')
//        ])->paperSize(80, $height)
//            ->margins(2,2,2,2);
//    }


//    /**
//     * @param MoneyCounter $counter
//     * @return Pdf
//     */
//    public function getB(MoneyCounter $counter)
//    {
//        return pdf::view('pdfs.InvoiceCounterB5', [
//            'setting' => Setting::first(),
//            'counter' => $counter,
//        ])->paperSize(80,240)
//            ->margins(2,2,2,2);
//    }
//
//    /**
//     * Generar el PDF
//     * @param Model $model
//     * @param string $pdfClass
//     * @param string $fileName
//     * @return JsonResponse
//     */
//    public function generatePDF (
//        Model $model,
//        string $pdfClass,
//        string $fileName
//    ){
//        try {
//            //Buscar el registro existente
////            $record = $modelClass::find($id);
//
//            // Verificar si no existe el registro.
//
//            // Eliminar el PDF si ya existe.
//            if (Storage::disk('pdfs')->exists($fileName)) {
//                Storage::disk('pdfs')->delete($fileName);
//            }
//
//            // Crear el PDF.
//            $pdf = new $pdfClass($model);
//            $pdfContent = $pdf->setData();
//
//            // Guardar el PDF en el almacenamiento.
//            Storage::disk('pdfs')->put($fileName, $pdfContent);
//
//            // Generar la URL del archivo.
//            $url = config('app.url') . '/storage/pdfs/' . $fileName;
//
//
//            // Devolver respuesta JSON con la URL.
//            return response()->json([
//                'msj' => 'PDF Generado y Guardado Temporalmente',
//                'url' => $url,
//            ]);
//
//        //Verificar si se puede generar el pdf
//        }catch (Exception $e){
//
//
//            return response()->json([
//                'Error' => 'No Es Posible Generar PDF',
//                'Info' => 'Error'.$e->getMessage(),
//            ]);
//        }
//
//    }
    /**
     * @param string $template
     * @return ResponseFactory|JsonResponse|Response
     * @throws ConnectionException
     */
    public function facturaCinta(string $template): ResponseFactory|JsonResponse|Response
    {
        $response = Http::attach('index.hmtl', $template, 'index.html')
            ->post($this->pdfGeneratorUrl, [
                'paperWidth' => '3.14',  // 80mm en pulgadas
                'marginLeft' => '0.1',
                'marginRight' => '0.1',
                'marginTop' => '0.1',    // Espacio para la cabecera fija
                'marginBottom' => '0.1',
                'waitDelay' => '600ms',  // Tiempo para que cargue Tailwind 4 por CDN
            ]);

        // Devolver si es correcto
        if ($response->successful()) {
            return response($response->body(), 200, [
                'content-type' => 'application/pdf'
            ]);
        }

        // Devolver mensaje de error
        return response()->json(['error' => 'Error al generar ticket'], 500);
    }

}

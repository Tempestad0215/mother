<?php

namespace App\Http\Controllers;

use App\Enums\PrintFormatEnum;
use App\Invoices\SaleInvoiceA;
use App\Invoices\Ticket80;
use App\Models\CashRegister;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\View\Components\Close;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Octane\Exceptions\DdException;
use PDF;
use Picqer\Barcode\BarcodeGeneratorPNG;

class InvoiceController extends Controller
{

    protected string $pdfGeneratorUrl;
    private string $userName;
    private string $password;


    public function __construct()
    {
        $this->pdfGeneratorUrl = config('appconfig.url_pdf').'/forms/chromium/convert/html';
        $this->userName = config('appconfig.user_name_pdf');
        $this->password = config('appconfig.user_password_pdf');
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
     * @throws \Throwable
     */
    public function getQuoteInvoice(Sale $sale)
    {
        $sale->load(['items.product', 'client']);

        $templateData = view('pdfs.quote.letter', [
            'sale' => $sale,
            'setting' => Setting::first(),
            'userName' => \Auth::user()->name
        ])->render();

        // Invoca Gotenberg con las dimensiones de Carta (8.5in x 11in)
        return $this->facturaCarta($templateData);
    }


    /**
     * Venta en formato Cinta (80mm)
     * @throws \Throwable
     */
    public function getSaleInvoice(Sale $sale)
    {
        $sale->load(['creditNotes', 'items']);

        $templateData = view('pdfs.sale.cinta', [
            'sale' => $sale,
            'setting' => Setting::first(),
            'creditNote' => $sale->creditNotes
        ])->render();

        return $this->facturaCinta($templateData);
    }



    /**
     * @param CreditNote $creditNote
     * @return ResponseFactory|JsonResponse|Response
     * @throws ConnectionException|DdException
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

    /**
     * @param CashRegister $cashRegister
     * @return ResponseFactory|JsonResponse|Response
     * @throws ConnectionException
     * @throws DdException
     */
    public function getCashRegisterClose(CashRegister $cashRegister)
    {
        $cashRegister->load(['user']);
        $setting = Setting::getGlobal();


        $component = new Close(
            setting: $setting,
            cashRegister: $cashRegister,
        );

        $template = $component->render()->with($component->data())->render();

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


    /**
     * @param string $template
     * @param PrintFormatEnum $format
     * @return ResponseFactory|JsonResponse|Response
     */
    public function generatePdf(
        string $template,
        PrintFormatEnum $format = PrintFormatEnum::Ticket80mm
    ): ResponseFactory|JsonResponse|Response
    {

        try {
            $response = Http::attach('index.html', $template, 'index.html')
                ->withBasicAuth($this->userName, $this->password)
                ->withOptions([
                    'verify' => false
                ])
                ->post($this->pdfGeneratorUrl, $format->dimensions());

            if ($response->successful()) {
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="documento.pdf"'
                ]);
            }else{
                Log::error($response->getStatusCode());
                return response()->json(['error' => 'Error al generar el PDF'], 500);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al generar el PDF'], 500);

        }

    }

    /* -------------------------------------------------------------------------- */
    /* MÉTODOS HELPER POR FORMATO                         */
    /* -------------------------------------------------------------------------- */

    /**
     * Helper para formato de cinta (POS 80mm)
     * @throws ConnectionException
     * @throws DdException
     */
    public function facturaCinta(string $template): ResponseFactory|JsonResponse|Response
    {
        return $this->generatePdf($template, PrintFormatEnum::Ticket80mm);
    }



    /**
     * Helper para formato Carta (Cotizaciones, Facturas A, etc.)
     * @throws ConnectionException|DdException
     */
    public function facturaCarta(string $template): ResponseFactory|JsonResponse|Response
    {
        return $this->generatePdf($template, PrintFormatEnum::Letter);
    }

    /**
     * Helper para formato A4 (Órdenes de Compra)
     * @throws ConnectionException|DdException
     */
    public function facturaA4(string $template): ResponseFactory|JsonResponse|Response
    {
        return $this->generatePdf($template, PrintFormatEnum::A4);
    }

}

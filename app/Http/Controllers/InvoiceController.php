<?php

namespace App\Http\Controllers;
use App\Invoices\SaleInvoiceA;
use App\Invoices\Ticket80;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\Sale;

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

        $html = view('pdfs.ticket.zebra',[
            'name' => 'Repuesto Camboya',
            'ref' => $product->sku,
            'code_bar' => $product->bar_code ? $product->bar_code : $product->code
        ])->render();

        $pdf = \Spatie\Browsershot\Browsershot::html($html)
            ->paperSize(10.16, 5.08, 'cm')
            ->margins(3,3,3,3)
            ->showBackground()
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Repuesto Camboya.pdf"');
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

}

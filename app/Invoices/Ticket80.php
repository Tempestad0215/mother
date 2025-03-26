<?php

namespace App\Invoices;

use App\Enums\SaleSerieEnum;
use App\Models\CreditNote;
use App\Models\Sale;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;
use TCPDF;

class Ticket80 extends TCPDF
{

    private ?Setting $setting;
    private int $headerEnd;
    private mixed $items;
    private \App\Helpers\General $money;
    private float $dataEnd;
    private bool $isSale;
    private Sale | CreditNote $model;
    private float $heightPage = 180;
    private float $headerHeight = 20;
//    private float $commentHeight = 0;

    public function __construct(Sale|CreditNote $model)
    {

        /*Pasar la variable al moedlo*/
        $this->model = $model;


//        Verificar a que pertenece
        if ($model instanceof Sale) {
            $this->items = $model->infoSale;
            $this->isSale = true;
        } else{
            $this->items = $model->trans;
            $this->isSale = false;
        }

        // Ajustar la altura de la página según los elementos
        collect($this->items)->each(function () {
            $this->heightPage += $this->headerHeight;
        });

//        Llamar el metodo principal para la configuracion
        parent::__construct("P", "mm", [80,$this->heightPage]);

//        Colocar la info de la setting
        $this->setting = Setting::first();


//        Instancia
        $this->money = new \App\Helpers\General();

//        Colocar la informacion para la factura
        $this->SetCreator("Tempestad");
        $this->SetAuthor("Tempestad");
        $this->SetTitle("Ticket 80");
        $this->SetKeywords("Ticket 80,Factura");
        $this->setMargins(2,2,2,true);
        $this->AddPage();


//        Llamar el metodo de los datos
        $this->setData();


    }


//    Titulo de la factura 80
    public function Header(): void
    {
//        Fuente de la factura
        $this->SetFont('helvetica', 'B', 16);
//        Nombre de la empresa
        $this->Cell(0, 5, $this->setting->name, 0, 1, 'C');
        $this->SetFont('helvetica', '', 8);
//        Rnc
        if($this->setting->company_id)
        {
            $this->Cell(0,3, $this->setting->company_id, 0, 1, 'C');
        }

        //        Rnc
        $this->Cell(0,3, $this->setting->email, 0, 1, 'C');
//        Direccion
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0,3, $this->setting->address, 0, 1, 'C');

        /**
         * Informacion de la factura
         */
        $this->Ln(3);
        $this->Line(2, $this->GetY(), 78, $this->GetY());
        $this->SetFont('helvetica', '', 12);
//        Si la sequencia existe se va a mostrar
        if ($this->setting->sequence)
        {
            $this->Cell(22,6, "NCF :", 0, 0, 'L');
            $this->Cell(30,6, $this->model->ncf, 0, 1, 'L');


//            si es nota de credito pues colocar el ncf modificado
            if (!$this->isSale)
            {
                $this->Cell(22,6, "NCF M.:", 0, 0, 'L');
                $this->Cell(30,6, $this->model->ncf_m, 0, 1, 'L');
            }
        }


//        Fecha de la factura
        $this->Cell(22,6, "Fecha :", 0, 0, 'L');
        $this->Cell(30,6, $this->model->created_at, 0, 0, 'L');


        /**
         * Informacion del cliente
         */
        $this->Ln(8);
        $this->Line(2, $this->GetY(), 78, $this->GetY());

        $this->Cell(0, 8,Str::replace("_", " ", SaleSerieEnum::from($this->model->invoice_type)->name) , 0, 1, 'C');

        $this->Line(2, $this->GetY(), 78, $this->GetY());


        $this->Setfont('helvetica', 'B', 12);
        $this->Cell(0,5, "Cliente ", 0, 1, 'C');

        $this->SetFont('helvetica', '', 11);
        $this->Cell(25, 5, "Nombre :", 1, 0, 'C');
        $this->Cell(0, 5, $this->model->client_name, 1, 1, 'C');
        $this->Cell(25, 5, "Documento :", 1, 0, 'C');
        $this->Cell(0, 5, $this->model->client_rnc, 1, 1, 'C');



        $this->headerEnd = $this->GetY();

    }


    /**
     * @return void
     */
    public function Footer(): void
    {

        $xValue = 20;
        /**
         * Informacion de pago de la factura
         */
        $this->SetY($this->dataEnd + 5);
        $this->SetX($xValue);
//     Itbis
        $this->Cell(30, 5, 'Itbis :', 0, 0, 'L');
        $this->Cell(30, 5, $this->money->moneyFormat($this->model->tax), 0, 1, 'L');
//     Descuiento
        $this->SetX($xValue);
        $this->Cell(30, 5, 'Descuento :', 0, 0, 'L');
        $this->Cell(30, 5, $this->money->moneyFormat($this->model->discount_amount), 0, 1, 'L');
        // Subtotak
        $this->SetX($xValue);
        $this->Cell(30, 5, 'Sub Total :', 0, 0, 'L');
        $this->Cell(30, 5, $this->money->moneyFormat($this->model->sub_total), 0, 1, 'L');
        // Total
        $this->SetX($xValue);
        $this->Cell(30, 5, 'Total :', 0, 0, 'L');
        $this->Cell(30, 5, $this->money->moneyFormat($this->model->amount), 0, 1, 'L');

        // Posición desde el final de la página
        $this->SetFont('helvetica', '', 8);
        $this->Ln(5);

        // Comentario
//        Inicio del comentario
//        $startComment = $this->GetY();
        $this->SetFont('helvetica', '', 10);
        $this->Cell(18, 5, "Comentario :", 0, 1, 'L');
             $this->MultiCell(0, 0,$this->model->comment, 0, 'L', '');

//             Tomar el final del comentario
//        $endComment = $this->GetY();

//        Colocar la altura final del comentario
//        $this->commentHeight = $endComment - $startComment;

        // Código de barras 1D (CODE 128)
        if (!empty($this->sale->code)) {
            $style = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 10,
                'stretchtext' => 4
            );

//            $this->SetY($endComment + 20);
            $this->Cell(0, 3, "Código de Factura:", 0, 1, 'C');
            $this->write1DBarcode($this->model->code, 'C128', '', '', 76, 20, 0.6, $style, 'N');
        }

        $printDate = Carbon::now()->format('d/m/Y H:i:s');

        $this->Cell(30, 5, 'Fecha Impresion :', 0, 0, 'C');
        $this->Cell(30, 5, $printDate, 0, 1, 'C');

//        Quien realizo la orden
        $this->Cell(30, 5, 'Le atendio :', 0, 0, 'C');
        $this->Cell(30, 5, $this->model->audits[0]->user->name, 0, 1, 'C');
        /**
         *
         */
        $this->Ln(5);

        // Mensaje de garantía
        $this->SetFont('helvetica', 'B', 7);
        $this->MultiCell(0, 5, "Las piezas eléctricas y piezas instaladas fuera del taller no tienen garantía.", 0, 'C');

        if (!$this->isSale)
        {
            // Agregar el comentario sobre las notas de crédito
            $this->Ln(3); // Añadir espacio entre los comentarios
            $this->SetFont('helvetica', 'B', 7);
            $this->MultiCell(0, 5, "Las notas de crédito deben ser consumidas antes de 30 días.", 0, 'C');
        }


        // Mensaje de agradecimiento
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(0, 5, "¡Gracias por preferirnos!", 0, 1, 'C');
    }


    /**
     * @return void
     */
    public function setData(): void
    {
        $this->SetY($this->headerEnd + 2);
        $this->Cell(0,5, 'Productos/Servicios',0,1,'C');
        /**
         * Cabcecera de la tabla
         */
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(8,5, 'Qt',1,0,'C');
        $this->Cell(50,5, 'Nombre',1,0,'C');
        $this->Cell(18,5, 'Importe',1,1,'C');
        /**
         * Informacion de la tabla
         */

        $this->SetFont('helvetica', '', 10);

        foreach ($this->items as $data)
        {
            $this->Cell(8,$this->headerHeight,$this->money->moneyFormat($data->stock) ,0,0,'C');

            $this->MultiCell(50,15,
                $data->product->code.PHP_EOL.
                str::limit($data->product->name, 45) .PHP_EOL
                .$data->price,0,'L', '', 0);

            $this->SetX(60);
            $this->Cell(0,$this->headerHeight, $this->money->moneyFormat($data->amount) ,0,1);


//            Line para dividir cada producto
            $this->Line(2,$this->GetY()-1, 78, $this->GetY()-1);

        }

//        Tomar el final de la tada
        $this->dataEnd = $this->GetY();

    }

}

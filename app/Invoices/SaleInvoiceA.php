<?php

namespace App\Invoices;


use App\Enums\ProductTransType;
use App\Models\ProTrans;
use App\Models\Sale;
use App\Models\Setting;
use Carbon\Carbon;
use TCPDF;
use Illuminate\Support\Str;


/**
 * @property  float $headerEnd
 * @property  int $line
 * @property  Setting $setting
 */
class SaleInvoiceA extends TCPDF
{
    protected float $headerEnd = 0;
    private int $line = 68;
    private Setting $setting;


    public function __construct(
        public Sale $sale,
        public $height = 200)
    {


        //Aumentar la linea por cada linea agregada
        $this->sale->infoSale->each(function (ProTrans $sale, int $index) {

            if ($index > 1) {
                //Aumenatr 10mm para que la factura quede bien
                $this->height += 3;
            }
        });


//        dd($this->sale->infoSale->count(),  $this->height);

        //Crea el formato de imprsion
        $format = array(72, $this->height);

        //Llmar el contructor
        parent::__construct('P', 'mm', $format);

        //Colocar el magen
        $this->SetMargins(2, 5, 2);
        //tomar la configuracion
        $this->setting = Setting::first();

        //Crear la pagina
        $this->AddPage();
    }


    /**
     * Cabecera de la pagina
     * @return void
     */
    public function Header():void{


        $this->setFont('helvetica', 'B', 14);

        //Titulo de la ventana
        $this->setY('10');
        $this->Cell(0, 5, $this->setting->name, 0, 1, 'C', 0, '', 0, false, 'M' );

        //Direccions
        $this->setFont('helvetica', '', 10);
        $this->MultiCell(0,0, $this->setting->address, 0, 'C', false, 1, '', '', true,true);

        $this->Ln(3);
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());
        //Rnc
        $this->Cell(20,5, 'RNC :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $this->setting->company_id, 0, 1, 'L', 0, '', 0, false, '' );

        //Telefono
        $this->Cell(20,5, 'Correo :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $this->setting->email, 0, 1, 'L', 0, '', 0, false, '' );

        //Telefono
        $this->Cell(20,5, 'Teléfono :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $this->setting->phone, 0, 1, 'L', 0, '', 0, false, '' );

        //Crear linea divisora
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());

        //Crae linea con dise;o
        $this->Ln(2);

        //Telefono
        $this->Cell(20,5, 'Fecha :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, $this->sale->created_at, 0, 1, 'L', 0, '', 0, false, '' );

        //Si existe secuencia pues se coloca el NCF
        if ($this->setting->sequence)
        {
            //Telefono
            $this->Cell(20,5, 'NCF :', 0, 0, 'L', false, '', '', false,'');
            $this->Cell(0, 5, $this->sale->ncf, 0, 1, 'L', 0, '', 0, false, '' );
        }else{
            //Telefono
            $this->Cell(20,5, 'Factura :', 0, 0, 'L', false, '', '', false,'');
            $this->Cell(0, 5, $this->sale->code, 0, 1, 'L', 0, '', 0, false, '' );
        }

        //Crear linea divisora
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());
        $this->Ln(2);

        /*
         * Datos del cliente
         */
        $this->Cell(0,5,'Cliente', 0, 1, 'C', false, '', '', false,'');

        //Nombre cliente
        $this->Cell(15,5, 'Nombre :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, $this->sale->client_name, 0, 1, 'L', 0, '', 0, false, '' );

        //si el cliente tiene RNC
        //RNC
        if ($this->sale->client_id !== null )
        {
            $this->Cell(15,5, 'Cliente :', 0, 0, 'L', false, '', '', false,'');
            $this->Cell(0, 5, $this->sale->client_name, 0, 1, 'L', 0, '', 0, false, '' );
        }


        //Crear linea divisora
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());
        $this->Ln(2);

        //Crea el encabezado de la tabla
        $headerTable = <<<EOD
                <table
                    style="border: 1px solid black;">
                    <tr
                        >
                        <th
                            style="width:12mm; border: 1px solid black;" >
                            Cant.</th>
                        <th
                            style="width:40mm; border: 1px solid black;"  >
                            Descripción</th>
                        <th
                             style="width:16mm; border: 1px solid black;">
                            Importe</th>
                    </tr>
                </table>
                EOD;


        //Crea el encabezado de los productos
        $this->writeHTML($headerTable, 1, false, true);


        //Tomar el ultimo valor de Y
        $this->headerEnd = $this->GetY();

    }


    /**
     * Poner el contenido y devolver los datos
     * @return string
     */

    public function setData():string
    {
        //ancho de la columna
        $width = array(12,38,16);

        //Craer la columna
        $this->SetY($this->headerEnd - 3);
        $this->setFont('helvetica', '', 8);



        //Crear la linea de los productos
        $this->sale->infoSale->where('type', '=', ProductTransType::VENTAS)
            ->each( function (ProTrans $item) use (&$width){

            //Cantidad vendida
            $this->Cell($width[0],5, number_format($item->stock,2), 0, 0, 'L', false, '', '');

            //Informaciond e productos
            $this->setFont('helvetica', '', 8);
            $this->Cell($width[0],5, $item->product->code, 0, 0, 'L', false, '', '');
            $this->setXY($width[0] + 2.5,$this->GetY()+3);
            $this->Cell($width[1],5, Str::limit($item->product->name, 20,  '...')  , 0, 0, 'L', false, '', 1);

            //Informacion de importe
            $this->setXY(54,$this->GetY()-3);
            $this->Cell($width[0],5,number_format($item->amount,2) , 0, 1, 'L', false, '', '');

            $this->setY($this->GetY()+3);
            $this->Line($this->GetX(),$this->GetY(),$this->GetX()+68,$this->GetY());

        });


        //Devolver el PDF como una cadena
        return $this->Output('','S');


    }



    /**
     * Pie de pagina del DPF
     * @return void
     */
    public function Footer():void
    {


        $xLocation = 18;


        $this->setY(-100);
        $this->setFont('helvetica', '', 10);
        //Crear linea divisora
//        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());

        $this->setX($xLocation);
        //Cantidad de articulo
        $this->Cell(30,5, 'Cantidad Artículo :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, $this->sale->infoSale->where('type',ProductTransType::VENTAS)->count(), 0, 1, 'L', 0, '', 0, false, '' );

        //Sub Total
        $this->setX($xLocation);
        $this->Cell(30,5, 'Sub Total :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, number_format($this->sale->sub_total,2), 0, 1, 'L', 0, '', 0, false, '' );

        //Descuento
        $this->setX($xLocation);
        $this->Cell(30,5, 'Descuento :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, number_format($this->sale->discount_amount,2), 0, 1, 'L', 0, '', 0, false, '' );

        //Gravado
        $this->setX($xLocation);
        $this->Cell(30,5, 'Gravado :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, number_format(($this->sale->sub_total - $this->sale->tax) ,2), 0, 1, 'L', 0, '', 0, false, '' );

        //Itbis
        $this->setX($xLocation);
        $this->Cell(30,5, 'Itbis :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, number_format($this->sale->tax ,2), 0, 1, 'L', 0, '', 0, false, '' );

        //Total
        $this->setX($xLocation);
        $this->Cell(30,5, 'Total :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, number_format($this->sale->amount ,2), 0, 1, 'L', 0, '', 0, false, '' );

        //Monto recibido
        $this->setX($xLocation);
        $this->Cell(30,5, 'Pago Con :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, number_format($this->sale->received ,2), 0, 1, 'L', 0, '', 0, false, '' );

        //Devuelta
        $this->setX($xLocation);
        $this->Cell(30,5, 'Devuelta :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, number_format($this->sale->returned ,2), 0, 1, 'L', 0, '', 0, false, '' );

        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());


        //Tomar la auditoria de creacion
        $audit = $this->sale->audits()->firstWhere('event','created');

        //Quien Creo la orden
        $this->Cell(30,5, 'Le Atendió :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, $audit->user->name  , 0, 1, 'L', 0, '', 0, false, '' );

        //Tipo de pago de la factura
        $this->Cell(30,5, 'Tipo Pago :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, $this->sale->type_payment->name  , 0, 1, 'L', 0, '', 0, false, '' );

        //Fechad e impresion
        $this->Cell(30,5, 'Fecha Impresión :', 0, 0, 'L', false, '', '', false,'');
        $this->Cell(0, 5, Carbon::now() , 0, 1, 'L', 0, '', 0, false, '' );


        //Crear codigo de barra

        $this->write1DBarcode(
            $this->sale->code,       // El valor del código de barras
            'C128',              // Tipo de código de barras (C128 es adecuado para alfanuméricos)
            10,                  // Posición X (se calcula automáticamente si se deja vacío)
            '',                  // Posición Y (se calcula automáticamente si se deja vacío)
            80,                  // Ancho del código de barras
            18,                  // Alto del código de barras
            0.4,                 // Escala
            [
                'border' => false,
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'position' => '',
                'fgcolor' => [0, 0, 0], // Color de primer plano
                'bgcolor' => [255, 255, 255], // Color de fondo
                'text' => true,         // Muestra el valor debajo del código de barras
                'label' => $this->sale->code // Muestra el valor del código debajo
            ],
        );



            //MEnsaje
        $this->Ln(5);
        $this->MultiCell(0,1,config('appconfig.msjInvoice'),0,'C');






    }



}

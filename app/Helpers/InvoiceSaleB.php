<?php

namespace App\Helpers;

use App\Http\Requests\StoreProductSaleRequest;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use NumberFormatter;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @property string $clientName Nombre del cliente
 * @property string $clientPhone Telefono del cliente
 * @property string $clientEmail Correo del cliente
 * @property bool $exitsClient Verifica si existe el cliente
 */


class InvoiceSaleB extends Fpdf
{


//    public string $clientName;
//    public string $clientPhone;
//    public string $clientEmail;
//    public bool $exitsClient;

    public $h;




    public  function __construct($h = 200)
    {
        parent::__construct('P','mm',array(80, $h));

        //Inicializar la variable
        $this->h = $h;

    }

    /**
     * @return void
     */
    function Header():void
    {
        //Llave para formatear a utf8
        $formatKey = [
            'phone' => mb_convert_encoding('Teléfono', 'ISO-8859-1', 'UTF-8'),
            ''
        ];

        $this->SetTitle('Factura Venta');
        //colocar el tipo de fuente
        $this->SetFont('Arial','',9);

//        //colocar la imagem
//        $this->Image($this->imagePath,0,0, 25);

        /**
         * Informacion del negocio
         */
        //Colocar la informacion de la empresa
        $this->SetY(5);
        $this->Cell(60,5,'GG TECH SERVICE',0,1,'C');
        $this->Cell(60,5,'+1 (829) 697-1098',0,1,'C');
        $this->Cell(60,5,'marioguzman140@gmail.com',0,1,'C');
        /**
         * Fecha e numero de factura
         */
        $this->Ln(1);
        $this->Cell(30, 5, 'Fecha : '.Carbon::now()->format('m/d/Y'),0,0,'L');
        $this->Cell(30,5, 'NO : FAC00000001',0,1,'L');


        $this->Line(5,26, 75, 26);
        $this->Ln(1);

        /**
         * Datos del cliente
         */
        //Nombre
        $this->Cell(10,5, 'Cliente',0,0,'L');
        $this->SetX(23);
        $this->Cell(10,5, ':',0,0,'L');
        $this->SetX(25);
        $this->Cell(10,5, 'Marionil Guzman Gonzalez',0,1,'L');

        //Telefono
        $this->Cell(10,5, $formatKey['phone'],0,0,'L');
        $this->SetX(23);
        $this->Cell(10,5, ':',0,0,'L');
        $this->SetX(25);
        $this->Cell(10,5, '(452) 1452-5222',0,1,'L');

        //Email
        $this->Cell(10,5, 'Email',0,0,'L');
        $this->SetX(23);
        $this->Cell(10,5, ':',0,0,'L');
        $this->SetX(25);
        $this->Cell(10,5, 'marioguzman140@gmail.com',0,1,'L');

        // Linea debajo del cliente
        $this->Line(5,43, 75, 43);
        $this->Ln(5);

        /**
         * Titulo para los datos
         */
        $this->SetX(2);
        $this->Cell(5,5, '#',1,0,'C');
        $this->Cell(40,5, 'Descripcion',1,0,'C');
        $this->Cell(15,5, 'Itbis',1,0,'C');
        $this->Cell(15,5, 'Valor',1,1,'C');


    }


    /**
     * @return void
     */
    function Footer():void
    {
        $thanks = mb_convert_encoding('¡Gracias por su compra!', 'ISO-8859-1', 'UTF-8');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $thanks, 0, 1, 'C');

    }



    public function setSaleInfo(float $tax, float $subTotal, float $total, float $discount = null, array $data = []):void
    {
        for($i = 0; $i < count($data); $i++) {


            //Sacar los nombres
            $name = $data[$i]['product_name'];
            $code = $data[$i]['code'];
            $price = $data[$i]['price'];
            $stock = $data[$i]['stock'];

            //Crear la descripcion del producto
            $description = $code."\n".$name."\n".$stock.'*'.$price;

            //Poner los datos en la lista
            $this->SetXY(2, $this->GetY()+4);
            $this->Cell(5, 5, $i+1, 0, 0, 'L');
            $this->SetXY(7, $this->GetY() - 4);
            $this->MultiCell(40,4,$description,0,'L');
            $this->SetXY(48, $this->GetY() - 9);
            $this->Cell(15,5, $data[$i]['tax'],0,0,'L');
            $this->Cell(15,5, $data[$i]['amount'],0,0,'L');
            $this->Line(2, $this->GetY() + 9.5, 78, $this->GetY() + 9.5);
            $this->Ln(10);
        }

        $this->Ln(3);

        //Colocar el calculo de la factura

        //Itbis
        $this->SetX(2);
        $this->Cell(20, 5, 'ITBIS',0,0,'L');
        $this->Cell(20, 5, ':',0,0,'L');
        $this->Cell(40, 5, $this->formatMoney($tax),0,1,'L');

        //Sub Total
        $this->SetX(2);
        $this->Cell(20, 5, 'Sub Total',0,0,'L');
        $this->Cell(20, 5, ':',0,0,'L');
        $this->Cell(40, 5, $this->formatMoney($subTotal),0,2,'L');

        //Descuento
        $this->SetX(2);
        $this->Cell(20, 5, 'Descuento',0,0,'L');
        $this->Cell(20, 5, ':',0,0,'L');
        $this->Cell(40, 5, $this->formatMoney($discount),0,2,'L');

        //Total
        $this->SetX(2);
        $this->Cell(20, 5, 'Total',0,0,'L');
        $this->Cell(20, 5, ':',0,0,'L');
        $this->Cell(40, 5, $this->formatMoney($total),0,2,'L');

        //Espacio
        $this->Ln(3);


    }



    private function formatMoney($amount):string {
        //Formatear los datos
        $formatter = new NumberFormatter('es_DO', NumberFormatter::CURRENCY);

        //DEvolver los datos
        return $formatter->format($amount);
    }


    /**
     * @param StoreProductSaleRequest $request
     * @return string
     */
    public function createPdf(StoreProductSaleRequest $request):string
    {

        //        Calcular la altura
        $this->h = 200;

        //Para aumentar el tamaño de la ventana
        for($i = 0; $i < count($request->info_sale); $i++){
            if(count($request->info_sale) > 2)
            {
                $this->h += 20;
            }
        }

        //Crear la pagina del PDF
        $this->AddPage();
        // Poner el tipo de fuente
        $this->SetFont('Courier', '', 8);

        //Colocar los datos de ventas
        $this->setSaleInfo($request->tax,
            $request->sub_total,
            $request->amount,
            $request->discount,
            $request->info_sale);


        //Colocar el comentario
        $this->SetX(2);
        $this->Cell(30,5, 'Comentario',0,0,'L');
        $this->SetX(22);
        $this->Cell(30,5, ':',0,1,'L');
        $this->SetX(5);
        $this->MultiCell(70,3, $request->comment, 0, 'L');

        //Poner el salto de pagina en no false
        $this->SetAutoPageBreak(false);

        // Codificar el pdf a base 64
        return base64_encode($this->Output('S','', true));
    }

}

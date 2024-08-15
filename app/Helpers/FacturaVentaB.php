<?php

namespace App\Helpers;

use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use NumberFormatter;

/**
 * @property string $imagePath Ruta de la imagen
 * @property string $clientName Nombre del cliente
 * @property string $clientPhone Telefono del cliente
 * @property string $clientEmail Correo del cliente
 * @property bool $exitsClient Verifica si existe el cliente
 */


class FacturaVentaB extends Fpdf
{


    private string $imagePath;
    public string $clientName;
    public string $clientPhone;
    public string $clientEmail;
    public bool $exitsClient;




    public  function __construct(int $h=200)
    {
        parent::__construct('P','mm',array(80, 200));
        $this->imagePath = public_path('storage/images/logo.jpg');
    }

    /**
     * @return void
     */
    function Header():void
    {
        //Llave para formatear a utf8
        $formatKey = [
            'phone' => utf8_decode('Teléfono'),
            ''
        ];

        $this->SetTitle('Factura Venta');
        //colocar el tipo de fuente
        $this->SetFont('Arial','',9);

        //colocar la imagem
        $this->Image($this->imagePath,0,0, 25);

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
        $thanks = utf8_decode('¡Gracias por su compra!');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $thanks, 0, 1, 'C');

    }



    public function setSaleInfo(float $tax, float $subTotal, float $total, float $discount = 0, array $data = []):void
    {
        for($i = 0; $i < count($data); $i++) {

            //Sacar los nombres
            $name = $data[$i]['name'];
            $code = $data[$i]['id'];
            $price = $data[$i]['price'];
            $quantity = $data[$i]['quantity'];

            //Crear la descripcion del producto
            $description = 'PRO00000'.$code."\n".$name."\n".$quantity.'*'.$price;

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



    private function formatMoney($amount, $currency = 'DOP', $locale = 'es_DO'):string {
        //Formatear los datos
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        //DEvolver los datos
        return $formatter->format($amount);
    }

}

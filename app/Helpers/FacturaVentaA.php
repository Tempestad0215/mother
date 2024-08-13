<?php

namespace App\Helpers;


use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;

class FacturaVentaA extends Fpdf
{
    public string $clientName;
    public string $clientPhone;
    public string $clientEmail;

    public function __construct($clientName, $clientPhone = "", $clientEmail = "")
    {
        // Llmar el constructor de pdf
        parent::__construct();

        //Pasar los datos a variable
        $this->clientName = $clientName;
        $this->clientPhone = $clientPhone;
        $this->clientEmail = $clientEmail;
    }

    function Header():void
    {
        //Obtener la ruta de la imagen
        $pathFile = public_path('storage/images/logo.jpg');

        // Titulo de la ventana

        // colocar la imagen
        $this->Image($pathFile,0,0,30);
        $this->SetTitle('Factura de venta', true);

        $this->SetFont('Arial', '', 12);
        //Datos de la empresa
        $this->SetXY(25,7);
        $this->cell(45, 5, "GG Tech Service", 0, 0, '');
        $this->SetXY(25,12);
        $this->cell(45, 5, "Mejores Servicios Tecnologico", 0, 0, '');
        $this->SetXY(25,17);
        $this->cell(45, 5, "105-02094-7", 0, 0, '');


        /**
         * DAtos de la factura
         */
        //Numero
        $no = utf8_decode('N°');
        $this->SetXY(-60,7);
        $this->cell(45, 5, $no."......: FAC0000001", 0, 0, '');
        // Fecha
        $this->SetXY(-60,12);
        $this->cell(45, 5, "Fecha.: 12/08/2024", 0, 0, '');


        /**
         * Datos del cliente
         */
        //Tranformar los datos
        $phone = utf8_decode('Teléfono');

        //Poner la fuente en negrita
        $this->SetFont('Arial', 'B', 12);

        //Crear un rectangulo
        $this->Rect(10,33, 100,20, 'D');
        // Colocar la informacion del cliente

        $this->SetXY(10,20);
        $this->cell(15,20, 'Info Cliente', 0, 0, '');
        $this->SetXY(15,35);
        $this->cell(150,5, 'Nombre :', 0, 0, '');
        $this->SetXY(15,40);
        $this->cell(150,5,  $phone.':', 0, 0, '');
        $this->SetXY(15,45);
        $this->cell(150,5,  'Email:', 0, 0, '');

        //Datos para el cliente dinamico
        $this->SetFont('Arial', '', 10);
        $this->SetXY(35,27.5);
        $this->cell(15,20, $this->clientName, 0, 0, '');
        $this->SetXY(35,32.5);
        $this->cell(15,20, $this->clientPhone, 0, 0, '');
        $this->SetXY(35,37.5);
        $this->cell(15,20, $this->clientEmail, 0, 0, '');



        $this->Ln(50);

    }


    function Footer():void
    {
        $this->setY(-15);
        $this->setFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }









}

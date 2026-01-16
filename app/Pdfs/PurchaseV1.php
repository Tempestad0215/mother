<?php

namespace App\Pdfs;

use Illuminate\Support\Facades\Storage;
use TCPDF;

class PurchaseV1 extends TCPDF
{

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'letter', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->setCreator(config('app.name'));
        $this->setTitle("Orden de Compra");
        $this->setKeywords("Orden de Compra, Compra, Orden",);

        $this->AddPage();
    }

    public function Header(): void
    {
        $image_file = public_path("logo.jpeg");

        if(!file_exists($image_file)){
            dump("no existe nada");
        }

        $this->Image($image_file, 5, 5, 25, 25, 'JPEG', '', 'T', false, 300, '', false, false, 0);
        $this->setXY(180,0);
        $this->setFont("Helvetica", "B", 16);
        $this->Cell(0,10, "Orden de Compra");

    }

    public function Footer():void
    {
        $this->setY(-15);
        $this->Cell(0,10, "Esta es la mejor de todas las coasa");
    }

}

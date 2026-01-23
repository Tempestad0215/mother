<?php

namespace App\Pdfs;

class ProductLabelV1 extends \TCPDF
{

    public function __construct($orientation = 'L', $unit = 'mm', $format = [25, 66], $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->setMargins(2,2,2);
        $this->SetAutoPageBreak(true, 0);
        $this->AddPage();
    }


    public function createInfo(string $code): void
    {
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => 1,
            'hpadding' => 2,
            'vpadding' => 2,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );

        $this->write1DBarcode($code, 'C128A', 2, 2, 0, 23, 0.5, $style, 'N');


    }


}

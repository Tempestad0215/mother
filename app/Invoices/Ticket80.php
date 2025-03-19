<?php

namespace App\Invoices;

use App\Models\Sale;
use App\Models\Setting;

class Ticket80 extends \TCPDF
{

    private ?Setting $setting;
    private Sale $sale;
    private int $headerEnd;

    public function __construct(Sale $sale)
    {
//        Llamar el metodo principal para la configuracion
        parent::__construct("P", "mm", [80,195]);

//        Colocar la info de la setting
        $this->setting = Setting::first();
        $this->sale = $sale;

//        Colocar la informacion para la factura
        $this->SetCreator("Tempestad");
        $this->SetAuthor("Tempestad");
        $this->SetTitle("Ticket 80");
        $this->SetKeywords("Ticket 80,Factura");
        $this->setMargins(2,2,2,true);
        $this->AddPage();


    }


//    Titulo de la factura 80
    public function Header()
    {
//        Fuente de la factura
        $this->SetFont('helvetica', 'B', 10);
//        Nombre de la empresa
        $this->Cell(0, 5, $this->setting->name, 0, 1, 'C');
        $this->SetFont('helvetica', '', 8);
//        Rnc
        $this->Cell(0,3, $this->setting->company_id, 0, 1, 'C');
        //        Rnc
        $this->Cell(0,3, $this->setting->email, 0, 1, 'C');
//        Direccion
        $this->SetFont('helvetica', '', 8);
        $this->Cell(0,3, $this->setting->address, 0, 1, 'C');

        /**
         * Informacion de la factura
         */
        $this->Ln(3);
        $this->Line(5, $this->GetY(), 75, $this->GetY());
//        Si la sequencia existe se va a mostrar
        if ($this->setting->sequence)
        {
            $this->Cell(10,5, "Factura :", 0, 0, 'L');
            $this->Cell(25,5, $this->sale->ncf, 0, 0, 'C');
        }


//        Fecha de la factura
        $this->Cell(8,5, "Fecha :", 0, 0, 'L');
        $this->Cell(30,5, $this->sale->created_at, 0, 0, 'C');


        /**
         * Informacion del cliente
         */
        $this->SetY($this->GetY()+2);
        $this->Ln(3);
        $this->Line(5, $this->GetY(), 75, $this->GetY());
        $this->Setfont('helvetica', 'B', 12);
        $this->Cell(0,5, "Cliente ", 0, 1, 'C');

        $this->SetFont('helvetica', '', 8);
        $this->Cell(20, 5, "Nombre :", 1, 0, 'C');
        $this->Cell(0, 5, $this->sale->client_name, 1, 1, 'C');
        $this->Cell(20, 5, "Documento :", 1, 0, 'C');
        $this->Cell(0, 5, $this->sale->client_rnc, 1, 1, 'C');


        $this->headerEnd = $this->GetY();

    }


    /**
     * @return void
     */
    public function Footer()
    {
        $this->SetY(-55); // Posición desde el final de la página
        $this->SetFont('helvetica', '', 8);

        // Comentario

        $this->Cell(0, 5, "Comentario :", 0, 1, 'L');
        $this->Cell(0, 5, $this->sale->comment, 0, 1, 'L');

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


            $this->Cell(0, 5, "Código de Factura:", 0, 1, 'C');
            $this->write1DBarcode($this->sale->code, 'C128', '', '', 76, 20, 0.6, $style, 'N');
        }

        // Mensaje de garantía
        $this->SetY(-20);
        $this->SetFont('helvetica', 'B', 7);
        $this->MultiCell(0, 5, "Las piezas eléctricas y piezas instaladas fuera del taller no tienen garantía.", 0, 'C');

        // Mensaje de agradecimiento
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(0, 5, "¡Gracias por preferirnos!", 0, 1, 'C');
    }

    public function setData()
    {
        $this->SetY($this->headerEnd + 2);
        $this->Cell(0,5, "Productos/Servicios",0,1,'C');


        /**
         * Datos de la tabla de productos
         */
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(36,5, "Nombre",1,0,'C');
        $this->Cell(20,5, "Cantidad",1,0,'C');
        $this->Cell(20,5, "Importe",1,0,'C');
    }
}

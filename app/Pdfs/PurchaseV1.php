<?php

namespace App\Pdfs;

use App\Models\Purchase;
use TCPDF;

class PurchaseV1 extends TCPDF
{

    protected array $headerW = [
        20,
        50,
        20,
        20,
        20,
        20,
        15,
        21
    ];
    private float $marginLeft = 12;
    private float $marginRight = 12;
    private float $headerEnd = 0;
    private float|int $pageWidth = 0;
    private float $coH = 8;
    private float $colRight = 120;

    public function __construct(
        Private readonly Purchase $purchase,
                                  $orientation = 'P',
                                  $unit = 'mm',
                                  $format = 'letter',
                                  $unicode = true,
                                  $encoding = 'UTF-8',
                                  $diskcache = false,
                                  $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->setCreator(config('app.name'));
        $this->setTitle("Orden de Compra");
        $this->setKeywords("Orden de Compra, Compra, Orden");


        $this->AddPage();

    }



    public function Header(): void
    {
        $this->pageWidth   = $this->getPageWidth(); // Letter width en mm
        $contentWidth = $this->pageWidth - $this->marginLeft - $this->marginRight;

        $colLeftX  = $this->marginLeft;     // columna izquierda
        $colRightX = $this->marginLeft + 120; // columna derecha (ajústalo si necesitas)

        // ==== 1) Logo (opcional) ====
        $logo = public_path('logo-demo.png'); // archivo ficticio en /public
        if (is_readable($logo)) {
            // x, y, w
            $this->Image($logo, $colLeftX, 12, 28, 0, '', '', 'T', false, 150);
        }

        // ==== 2) Datos de la empresa (columna izquierda) ====
        // Arrancamos a la derecha del logo para no superponer
        $this->SetXY($colLeftX + 32, 12);
        $this->SetFont('Helvetica', 'B', 13);
        $this->Cell(88, 6, 'Compañía Ficticia S.A.', 0, 2);

        $this->SetFont('Helvetica', '', 9);
        $this->Cell(88, 5, 'RNC: 1-00-00000-0', 0, 2);
        $this->Cell(88, 5, 'demo@compania-ficticia.test', 0, 2);
        $this->Cell(88, 5, '(809) 000-0000', 0, 2);
        $this->MultiCell(88, 5, 'Calle Imaginaria #123, Sector Inventado, Ciudad Demo', 0, 'L', false, 2);

        // ==== 3) Título y datos de la OC (columna derecha) ====
        $this->SetXY($colRightX, 12);
        $this->SetFont('Helvetica', 'B', 16);
        $this->Cell($contentWidth - 120, 8, 'ORDEN DE COMPRA', 0, 2, 'R');

        $this->SetFont('Helvetica', '', 10);
        $this->SetTextColor(60, 60, 60);
        $this->Cell($contentWidth - 120, 6, 'N.º OC: OC-000123', 0, 2, 'R');
        $this->Cell($contentWidth - 120, 6, 'Fecha: 2026-01-18', 0, 2, 'R');
        $this->Cell($contentWidth - 120, 6, 'Términos: 30 días', 0, 2, 'R');

        // ==== 4) Línea separadora de la cabecera ====
        $lineY = 42; // deja la cabecera hasta ~42 mm
        $this->SetDrawColor(180, 180, 180);
        $this->Line($this->marginLeft, $lineY, $this->pageWidth - $this->marginRight, $lineY);

        // ==== 5) Bloque del proveedor (DEBAJO de la línea) ====
        // Reubicar el bloque del proveedor para que jamás se superponga
        $boxTopY = $lineY + 4; // espacio debajo de la línea
        $this->SetY($boxTopY);

        // Opcional: dibujar un rectángulo suave para el bloque del proveedor
        $boxHeight = 20; // ajusta según tus campos
        $this->SetDrawColor(220, 220, 220);
        $this->Rect($this->marginLeft, $boxTopY, $contentWidth, $boxHeight);

        // Etiquetas + contenido (ficticio)
        $this->SetXY($this->marginLeft + 3, $boxTopY + 3);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(28, 6, 'Proveedor:');
        $this->SetFont('Helvetica', '', 10);
        $this->Cell(90, 6, 'Distribuidora Demo SRL');

        $this->SetXY($this->marginLeft + 3, $boxTopY + 9);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(28, 6, 'RNC:');
        $this->SetFont('Helvetica', '', 10);
        $this->Cell(60, 6, '1-23-45678-9');

        $this->SetXY($this->marginLeft+ 80, $boxTopY + 9);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(28, 6, 'Dirección:');
        $this->SetFont('Helvetica', '', 10);
        $this->Cell(0, 6, 'Ave. Prueba #45, Parque Industrial, Ciudad');

        // Avanza el cursor final del header (inicio del contenido)
        $this->setY($boxTopY + $boxHeight + 4);
        $this->setX($this->marginLeft);



        $this->Cell($this->headerW[0], $this->coH, "  Codigo", 1);
        $this->Cell($this->headerW[1], $this->coH, "  Producto/Servicio",1);
        $this->Cell($this->headerW[2], $this->coH, "  Cantidad",1);
        $this->Cell($this->headerW[3], $this->coH, "  Costo",1);
        $this->Cell($this->headerW[4], $this->coH, "  Descuento",1);
        $this->Cell($this->headerW[5], $this->coH, "  Impuesto",1);
        $this->Cell($this->headerW[6], $this->coH, "  Almcen",1);
        $this->Cell($this->headerW[7], $this->coH, "  Importe",1, 2);

        $this->headerEnd = $this->GetY();


    }


    public function renderData():void
    {
        $this->setY($this->headerEnd + 2);
        $this->setX($this->marginLeft);

        $boxWidth = $this->pageWidth - $this->marginRight - $this->marginLeft;

        $this->setDrawColor(220, 220, 220);
        $this->Rect($this->marginLeft, $this->GetY() , $boxWidth, 150);
        $this->setFont('Helvetica', '', 8);
        foreach ($this->purchase->items as $item)
        {
            $this->Cell($this->headerW[0], $this->coH, $item->product->code, 1);
            $this->Cell($this->headerW[1], $this->coH, $item->product->name, 1);
            $this->Cell($this->headerW[2], $this->coH, number_format($item->quantity,2) , 1);
            $this->Cell($this->headerW[3], $this->coH, number_format($item->cost+330000,2), 1);
            $this->Cell($this->headerW[4], $this->coH, number_format($item->discount,2) , 1);
            $this->Cell($this->headerW[5], $this->coH, $item->tax->name, 1);
            $this->Cell($this->headerW[6], $this->coH, $item->warehouse->name, 1);
            $this->Cell($this->headerW[7], $this->coH, number_format($item->amount,2), 1,1);
            $this->setX($this->marginLeft);
        }

    }



    public function Footer():void
    {
        $h = $this->coH;
        $right = $this->colRight + 8;

        $footerStart = 150 + $this->headerEnd;


        // total de la factura
        $this->setXY($right, $footerStart + 5);
        $this->Cell(30, $h, "Descuento :", 1,0, "C");
        $this->Cell(40, $h, number_format($this->purchase->discount),1,1,"C");
        $this->setX($right);
        $this->Cell(30, $h, "Itbis :", 1,0, "C");
        $this->Cell(40, $h, number_format($this->purchase->tax),1,1,"C");
        $this->setX($right);
        $this->Cell(30, $h, "Sub Total :", 1,0, "C");
        $this->Cell(40, $h, number_format($this->purchase->sub_total),1,1,"C");
        $this->setX($right);
        $this->Cell(30, $h, "Total :", 1,0, "C");
        $this->Cell(40, $h, number_format($this->purchase->amount),1,1,"C");


        // Detalle de la factura



    }

}

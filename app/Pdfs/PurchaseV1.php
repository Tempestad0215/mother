<?php

namespace App\Pdfs;

use Illuminate\Support\Facades\Storage;
use TCPDF;

class PurchaseV1 extends TCPDF
{

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'letter', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

// Deja más espacio arriba para el header (≈45 mm)
//        $this->SetMargins(12, 52, 12);      // izq, sup, der
//        $this->SetHeaderMargin(50);
//        $this->SetFooterMargin(12);


        $this->setCreator(config('app.name'));
        $this->setTitle("Orden de Compra");
        $this->setKeywords("Orden de Compra, Compra, Orden",);

        $this->AddPage();
    }



    public function Header(): void
    {
        $marginLeft  = 12;
        $marginRight = 12;
        $pageWidth   = $this->getPageWidth(); // Letter width en mm
        $contentWidth = $pageWidth - $marginLeft - $marginRight;

        $colLeftX  = $marginLeft;     // columna izquierda
        $colRightX = $marginLeft + 120; // columna derecha (ajústalo si necesitas)

        // ==== 1) Logo (opcional) ====
        $logo = public_path('logo-demo.png'); // archivo ficticio en /public
        if (is_readable($logo)) {
            // x, y, w
            $this->Image($logo, $colLeftX, 12, 28, 0, '', '', 'T', false, 300);
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
        $this->Line($marginLeft, $lineY, $pageWidth - $marginRight, $lineY);

        // ==== 5) Bloque del proveedor (DEBAJO de la línea) ====
        // Reubicar el bloque del proveedor para que jamás se superponga
        $boxTopY = $lineY + 4; // espacio debajo de la línea
        $this->SetY($boxTopY);

        // Opcional: dibujar un rectángulo suave para el bloque del proveedor
        $boxHeight = 20; // ajusta según tus campos
        $this->SetDrawColor(220, 220, 220);
        $this->Rect($marginLeft, $boxTopY, $contentWidth, $boxHeight);

        // Etiquetas + contenido (ficticio)
        $this->SetXY($marginLeft + 3, $boxTopY + 3);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(28, 6, 'Proveedor:', 0, 0);
        $this->SetFont('Helvetica', '', 10);
        $this->Cell(90, 6, 'Distribuidora Demo SRL', 0, 0);

        $this->SetXY($marginLeft + 3, $boxTopY + 9);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(28, 6, 'RNC:', 0, 0);
        $this->SetFont('Helvetica', '', 10);
        $this->Cell(60, 6, '1-23-45678-9', 0, 0);

        $this->SetXY($marginLeft + 80, $boxTopY + 9);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(28, 6, 'Dirección:', 0, 0);
        $this->SetFont('Helvetica', '', 10);
        $this->Cell(0, 6, 'Ave. Prueba #45, Parque Industrial, Ciudad', 0, 0);

        // Avanza el cursor final del header (inicio del contenido)
        $this->SetY($boxTopY + $boxHeight + 4);
    }


    public function renderTablaCompraFromData(array $rows, array $totals, array $opts = []): void
    {
        // Opciones de formato
        $currency         = $opts['currency']          ?? 'RD$';
        $decimals         = $opts['decimals']          ?? 2;
        $dec_point        = $opts['decimal_separator'] ?? ',';
        $thousands_sep    = $opts['thousands_sep']     ?? '.';
        $rowHeight        = $opts['row_height']        ?? 8;

        // Anchos de columnas (suman 192mm = 216 - 12 - 12)
        $w = [
            'codigo'   => 20,
            'producto' => 60,
            'cantidad' => 16,
            'costo'    => 18,
            'desc'     => 18,
            'imp'      => 18,
            'almacen'  => 22,
            'importe'  => 20,
        ];

        // Encabezado de tabla
        $this->renderEncabezadoTablaCompra($w, $rowHeight);

        // Filas (NO se calculan valores; se imprimen los recibidos)
        $this->SetFont('Helvetica', '', 9);
        $this->SetDrawColor(210, 210, 210);

        foreach ($rows as $r) {
            // Control de salto de página
            if ($this->GetY() + $rowHeight > ($this->getPageHeight() - $this->bMargin - 28)) {
                $this->AddPage();
                $this->renderEncabezadoTablaCompra($w, $rowHeight);
            }

            $codigo   = (string)($r['codigo']   ?? '');
            $producto = (string)($r['producto'] ?? '');
            $cantidad = $r['cantidad'] ?? '';
            $costo    = $r['costo']    ?? '';
            $desc     = $r['descuento']?? '';
            $imp      = $r['impuesto'] ?? '';
            $almacen  = (string)($r['almacen']  ?? '');
            $importe  = $r['importe']  ?? '';

            // Imprimir fila tal cual (formateo mínimo opcional para números)
            $this->Cell($w['codigo'],   $rowHeight, $this->truncate($codigo,   $w['codigo']),   1, 0, 'L');
            $this->Cell($w['producto'], $rowHeight, $this->truncate($producto, $w['producto']), 1, 0, 'L');
            $this->Cell($w['cantidad'], $rowHeight, is_numeric($cantidad) ? $this->fmtNum((float)$cantidad, 0, $dec_point, $thousands_sep) : (string)$cantidad, 1, 0, 'C');
            $this->Cell($w['costo'],    $rowHeight, is_numeric($costo)    ? $this->fmtMoney((float)$costo, $currency, $decimals, $dec_point, $thousands_sep) : (string)$costo, 1, 0, 'R');
            $this->Cell($w['desc'],     $rowHeight, is_numeric($desc)     ? $this->fmtMoney((float)$desc,  $currency, $decimals, $dec_point, $thousands_sep) : (string)$desc,  1, 0, 'R');
            $this->Cell($w['imp'],      $rowHeight, is_numeric($imp)      ? $this->fmtMoney((float)$imp,   $currency, $decimals, $dec_point, $thousands_sep) : (string)$imp,   1, 0, 'R');
            $this->Cell($w['almacen'],  $rowHeight, $this->truncate($almacen,  $w['almacen']),  1, 0, 'L');
            $this->Cell($w['importe'],  $rowHeight, is_numeric($importe)  ? $this->fmtMoney((float)$importe, $currency, $decimals, $dec_point, $thousands_sep) : (string)$importe, 1, 1, 'R');
        }

        // Bloque de totales (usa exactamente lo que envías)
        $this->renderTotalesCompra($w, $totals, $currency, $decimals, $dec_point, $thousands_sep);
    }

    /** Encabezado de columnas de la tabla de compra */
    protected function renderEncabezadoTablaCompra(array $w, float $rowHeight = 8): void
    {
        $this->SetFont('Helvetica', 'B', 9);
        $this->SetFillColor(245, 245, 245);
        $this->SetDrawColor(190, 190, 190);
        $this->Cell($w['codigo'],   $rowHeight, 'Código',    1, 0, 'L', true);
        $this->Cell($w['producto'], $rowHeight, 'Producto',  1, 0, 'L', true);
        $this->Cell($w['cantidad'], $rowHeight, 'Cant.',     1, 0, 'C', true);
        $this->Cell($w['costo'],    $rowHeight, 'Costo',     1, 0, 'R', true);
        $this->Cell($w['desc'],     $rowHeight, 'Desc.',     1, 0, 'R', true);
        $this->Cell($w['imp'],      $rowHeight, 'Imp.',      1, 0, 'R', true);
        $this->Cell($w['almacen'],  $rowHeight, 'Almacén',   1, 0, 'L', true);
        $this->Cell($w['importe'],  $rowHeight, 'Importe',   1, 1, 'R', true);
        $this->SetFont('Helvetica', '', 9);
    }

    /** Bloque de totales al final (sin cálculos, solo pintado) */
    protected function renderTotalesCompra(array $w, array $totals, string $currency, int $decimals, string $dec_point, string $thousands_sep): void
    {
        $blockWidth = $w['costo'] + $w['desc'] + $w['imp'] + $w['importe']; // 18+18+18+20 = 74mm
        $rowHeight  = 8;

        $xRight = $this->getPageWidth() - $this->rMargin - $blockWidth;
        $yStart = $this->GetY() + 2;

        // Saltar de página si no cabe el bloque completo (4 filas)
        if ($yStart + ($rowHeight * 4) > ($this->getPageHeight() - $this->bMargin)) {
            $this->AddPage();
            $yStart = $this->GetY();
        }

        $this->SetXY($xRight, $yStart);
        $this->SetDrawColor(190, 190, 190);
        $this->SetFillColor(250, 250, 250);

        // Subtotal
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell($blockWidth - 34, $rowHeight, 'Subtotal', 1, 0, 'R', true);
        $this->SetFont('Helvetica', '', 9);
        $this->Cell(34, $rowHeight, $this->formatMaybeMoney($totals['subtotal'] ?? '', $currency, $decimals, $dec_point, $thousands_sep), 1, 1, 'R', true);

        // Descuento
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell($blockWidth - 34, $rowHeight, 'Descuento', 1, 0, 'R', true);
        $this->SetFont('Helvetica', '', 9);
        $this->Cell(34, $rowHeight, $this->formatMaybeMoney($totals['descuento'] ?? '', $currency, $decimals, $dec_point, $thousands_sep), 1, 1, 'R', true);

        // Impuesto
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell($blockWidth - 34, $rowHeight, 'Impuesto', 1, 0, 'R', true);
        $this->SetFont('Helvetica', '', 9);
        $this->Cell(34, $rowHeight, $this->formatMaybeMoney($totals['impuesto'] ?? '', $currency, $decimals, $dec_point, $thousands_sep), 1, 1, 'R', true);

        // Total
        $this->SetFont('Helvetica', 'B', 10);
        $this->SetFillColor(240, 248, 255);
        $this->Cell($blockWidth - 34, $rowHeight + 1, 'Total', 1, 0, 'R', true);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(34, $rowHeight + 1, $this->formatMaybeMoney($totals['total'] ?? '', $currency, $decimals, $dec_point, $thousands_sep), 1, 1, 'R', true);
    }

    /** Formatea moneda solo si es numérico; si envías string ya formateado, lo imprime tal cual */
    protected function formatMaybeMoney($value, string $currency, int $decimals, string $dec_point, string $thousands_sep): string
    {
        if (is_numeric($value)) {
            return $this->fmtMoney((float)$value, $currency, $decimals, $dec_point, $thousands_sep);
        }
        return (string)$value;
    }

// Utilidades que ya usamos antes
    protected function fmtMoney(float $n, string $currency, int $decimals, string $dec_point, string $thousands_sep): string
    {
        return $currency . ' ' . number_format($n, $decimals, $dec_point, $thousands_sep);
    }
    protected function fmtNum(float $n, int $decimals, string $dec_point, string $thousands_sep): string
    {
        return number_format($n, $decimals, $dec_point, $thousands_sep);
    }
    protected function truncate(string $text, float $cellWidth): string
    {
        $max = $cellWidth - 2;
        if ($this->GetStringWidth($text) <= $max) return $text;
        $ellipsis = '…';
        while ($text !== '' && $this->GetStringWidth($text . $ellipsis) > $max) {
            $text = mb_substr($text, 0, -1);
        }
        return $text . $ellipsis;
    }



    public function Footer():void
    {
        $this->setY(-15);
        $this->Cell(0,10, "Esta es la mejor de todas las coasa");
    }

}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $sale->code }}</title>
    <style>
        @page {
            size: letter;
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
        }

        /* Utilidades de Maquetación */
        .w-full { width: 100%; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .align-center { align-items: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Cabecera y Datos de Empresa */
        .header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-title {
            font-size: 22px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }
        .document-title {
            font-size: 26px;
            font-weight: 800;
            color: #2563eb;
            text-align: right;
            letter-spacing: 1px;
        }

        /* Cajas de Información (Cliente / Fecha) */
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        .info-box {
            width: 48%;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px 15px;
        }
        .info-box-title {
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }

        /* Tabla de Productos */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Totales */
        .totals-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }
        .totals-table {
            width: 40%;
        }
        .totals-table td {
            padding: 6px 10px;
        }
        .total-row td {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            border-top: 2px solid #1e293b;
            background-color: #f1f5f9;
        }

        /* Términos y Firmas */
        .terms-and-signatures {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 200px;
            border-top: 1px solid #9ca3af;
            text-align: center;
            padding-top: 5px;
            font-size: 11px;
            color: #4b5563;
        }
    </style>
</head>
<body>

<div class="header flex justify-between align-center">
    <div>
        <div class="company-title">{{ $setting->name }}</div>
        <p>RNC: {{ $setting->company_id }}</p>
        <p>Teléfono: {{ $setting->phone }}</p>
        <p>Puerto Plata, República Dominicana</p>
    </div>
    <div>
        <div class="document-title">COTIZACIÓN</div>
        <p class="text-right font-bold" style="font-size: 14px; color: #4b5563;"># {{ $sale->code }}</p>
    </div>
</div>

<div class="info-grid">
    <div class="info-box">
        <div class="info-box-title">Cliente</div>
        <p class="font-bold">{{ $sale->client_name }}</p>
        <p>RNC / Cédula: {{ $sale->customer?->rnc ?? 'N/A' }}</p>
        <p>Teléfono: {{ $sale->customer?->phone ?? 'N/A' }}</p>
    </div>

    <div class="info-box">
        <div class="info-box-title">Detalles de la Oferta</div>
        <p><strong>Fecha de Emisión:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y') }}</p>
        <p><strong>Válida hasta:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->addDays(15)->format('d/m/Y') }}</p>
        <p><strong>Atendido por:</strong> {{ $userName ?? 'Ventas' }}</p>
    </div>
</div>

<table>
    <thead>
    <tr>
        <th style="width: 15%;">CÓDIGO</th>
        <th style="width: 45%;">DESCRIPCIÓN</th>
        <th class="text-center" style="width: 10%;">CANT.</th>
        <th class="text-right" style="width: 15%;">PRECIO</th>
        <th class="text-right" style="width: 15%;">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sale->items as $item)
        <tr>
            <td class="font-bold">{{ $item->product->code }}</td>
            <td>{{ $item->product->name }}</td>
            <td class="text-center">{{ number_format($item->stock, 2) }}</td>
            <td class="text-right">RD$ {{ number_format($item->price, 2) }}</td>
            <td class="text-right font-bold">RD$ {{ number_format($item->amount, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="totals-container">
    <table class="totals-table">
        <tr>
            <td class="font-bold">Sub-Total:</td>
            <td class="text-right">RD$ {{ number_format($sale->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td class="font-bold">ITBIS (18%):</td>
            <td class="text-right">RD$ {{ number_format($sale->tax, 2) }}</td>
        </tr>
        @if($sale->discount_amount > 0)
            <tr>
                <td class="font-bold" style="color: #dc2626;">Descuento:</td>
                <td class="text-right" style="color: #dc2626;">-RD$ {{ number_format($sale->discount_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td>TOTAL:</td>
            <td class="text-right">RD$ {{ number_format($sale->amount, 2) }}</td>
        </tr>
    </table>
</div>

<div class="terms-and-signatures">
    <div style="background-color: #f3f4f6; padding: 12px; border-radius: 6px; margin-bottom: 50px;">
        <p class="font-bold" style="margin-bottom: 4px;">Términos y Condiciones:</p>
        <ul style="margin: 0; padding-left: 20px; font-size: 11px; color: #4b5563;">
            <li>Esta cotización tiene una validez de 15 días a partir de la fecha de emisión.</li>
            <li>Los precios están sujetos a cambios sin previo aviso según la disponibilidad de stock.</li>
        </ul>
    </div>

    <div class="flex justify-between" style="padding: 0 40px;">
        <div class="signature-box">Preparado por</div>
        <div class="signature-box">Aceptado por (Cliente)</div>
    </div>
</div>

</body>
</html>

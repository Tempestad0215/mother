@php
    use App\Models\Setting;
    use Illuminate\Support\Carbon;
@endphp
@props([
    'setting',
    'documentType',
    'documentCode',
    'documentDate',
])

@php
    /**
     * @var Setting $setting
     * @var string $documentType
     * @var string $documentCode
     * @var Carbon|string $documentDate
     */
@endphp

    <!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
        }

        body {
            width: 72mm; /* Ancho imprimible estándar para papel de 80mm */
            margin: 0 auto;
            padding: 4px;
            color: #000;
            background-color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .company-info {
            font-size: 13px;
            line-height: 1.3;
            margin-bottom: 8px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .meta-table {
            width: 100%;
            font-size: 12px;
            font-weight: bold;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
        }
    </style>
</head>
<body>

<!-- Encabezado del Negocio -->
<div class="text-center">
    <h2 class="title">{{ config('app.name') }}</h2>
    <div class="company-info">
        <p>RNC: {{ $setting->company_id }}</p>
        <p>Tel: {{ $setting->phone }}</p>
        <p>{{ $setting->address }}</p>
    </div>
</div>

<div class="divider"></div>

<!-- Datos del Documento -->
<table class="meta-table">
    <tr>
        <td>DOCUMENTO:</td>
        <td>{{ $documentType }}</td>
        <td class="text-right">{{ $documentCode }}</td>
    </tr>
    <tr>
        <td colspan="2">FECHA:</td>
        <td class="text-right">{{ $documentDate }}</td>
    </tr>
</table>

<div class="divider"></div>

{{-- Punto de inserción para el resto de la factura --}}
{{ $slot }}

</body>
</html>

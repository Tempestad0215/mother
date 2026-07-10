<!DOCTYPE html>
<html lang="es">

<head>
    @php
        /** @var App\Models\Sale $sale */
        /** @var App\Models\Setting $setting */
        /** @var \Ramsey\Collection\Collection<int, \App\Models\CreditNote> $creditNote */
    @endphp
    <meta charset="UTF-8">
    <title>Factura {{ $sale->code }}</title>

    <style>
        /* 1. Configuraciones de impresión para Gotenberg (Chromium) */
        /*@page {*/
        /*    size: 80mm auto;*/
        /*    margin: 0;*/
        /*}*/

        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #ffffff;
            color: #000000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding-left: 8px;   /* px-2 */
            padding-right: 8px;  /* px-2 */
            padding-top: 16px;   /* pt-4 */
            padding-bottom: 0px; /* pb-0 */
            margin-left: auto;   /* mx-auto */
            margin-right: auto;  /* mx-auto */
            width: 72mm;
        }

        /* Ocultar elementos innecesarios al imprimir */
        @media print {
            .no-print {
                display: none;
            }
        }

        /* 2. Estilos estructurales y tipografía */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        .text-xl { font-size: 20px; }
        .text-sm { font-size: 14px; }
        .text-xs { font-size: 12px; }
        .text-10px { font-size: 10px; }
        .text-8px { font-size: 8px; }

        .font-bold { font-weight: bold; }
        .font-black { font-weight: 900; }

        .uppercase { text-transform: uppercase; }
        .tracking-wide { letter-spacing: 0.025em; }
        .leading-relaxed { line-height: 1.625; }
        .break-words { word-wrap: break-word; word-break: break-all; }

        /* 3. Colores específicos */
        .text-gray-600 { color: #4b5563; }
        .text-gray-700 { color: #374151; }
        .text-white { color: #ffffff; }

        /* 4. Separadores térmicos (Bordes) */
        .border-t-dashed-black { border-top: 1px dashed #000000; }
        .border-b-dashed-black { border-bottom: 1px dashed #000000; }
        .border-b-dotted-gray { border-bottom: 1px dotted #9ca3af; }

        /* 5. Flexbox para las filas alineadas */
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }

        /* 6. Márgenes y Rellenos (Spacings) */
        .my-2 { margin-top: 8px; margin-bottom: 8px; }
        .my-3 { margin-top: 12px; margin-bottom: 12px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }

        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }

        .pt-1 { padding-top: 4px; }
        .pt-2 { padding-top: 8px; }
        .pb-1 { padding-bottom: 4px; }
        .py-1 { padding-top: 4px; padding-bottom: 4px; }
        .p-2 { padding: 8px; }

        /* Emulación de space-y de Tailwind */
        .space-y-5 > * + * { margin-top: 2px; }
        .space-y-1 > * + * { margin-top: 4px; }

        /* 7. Tablas y bloques del ticket */
        .w-full { width: 100%; }
        .w-85-percent { width: 85%; }
        .ml-auto { margin-left: auto; }
        .border-collapse { border-collapse: collapse; }

        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Reseteo básico de elementos internos */
        p { margin: 0; }
    </style>
</head>

<body>

<div class="text-center mb-4">
    <h1 class="text-xl font-black tracking-wide uppercase">{{ $setting->name }}</h1>
    <p class="text-xs leading-relaxed mt-1">
        RNC: {{ $setting->company_id }}<br>
        Tel: {{ $setting->phone }}<br>
        Puerto Plata, Rep. Dominicana
    </p>
</div>

<div class="border-t-dashed-black my-2"></div>

<div class="text-xs space-y-0.5 mb-2">
    <div class="flex justify-between">
        <span class="font-bold">FACTURA:</span>
        <span>{{ $sale->code }}</span>
    </div>
    @if ($sale->ncf)
        <div class="flex justify-between">
            <span class="font-bold">NCF:</span>
            <span>{{ $sale->ncf ?? 'N/A' }}</span>
        </div>
    @endif

    <div class="flex justify-between">
        <span class="font-bold">FECHA:</span>
        <span>{{ $sale->created_at }}</span>
    </div>
    <div class="flex justify-between">
        <span class="font-bold">CLIENTE:</span>
        <span class="text-right truncate" style="max-width: 45mm;">{{ $sale->client_name }}</span>
    </div>
</div>

<div class="border-t-dashed-black my-2"></div>

<table class="w-full text-xs border-collapse">
    <thead>
    <tr class="border-b-dashed-black font-bold">
        <th class="text-left py-1" style="width: 55%;">DESCRIPCIÓN</th>
        <th class="text-center py-1" style="width: 15%;">CANT</th>
        <th class="text-right py-1" style="width: 30%;">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sale->items as $item)
        <tr class="font-bold">
            <td class="pt-2 text-left uppercase break-words">
                <p>{{ $item->product->code }}</p>
                <p>{{ Str::limit($item->product->name, 15) }}</p>
            </td>
            <td class="pt-2 text-center" style="vertical-align: top;">{{ number_format($item->stock, 2) }}</td>
            <td class="pt-2 text-right" style="vertical-align: top;">{{ number_format($item->amount, 2) }}</td>
        </tr>
        <tr class="text-10px text-gray-700 border-b-dotted-gray">
            <td colspan="3" class="pb-1 text-left">
                Precio Unit: {{ number_format($item->price, 2) }}
                @if($item->discount_amount > 0)
                    (Desc: -{{ number_format($item->discount_amount, 2) }})
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="border-t-dashed-black my-2"></div>

<div class="w-85-percent ml-auto text-xs space-y-1">
    <div class="flex justify-between">
        <span>Sub-Total:</span>
        <span>RD$ {{ number_format($sale->sub_total, 2) }}</span>
    </div>
    <div class="flex justify-between">
        <span>ITBIS (18%):</span>
        <span>RD$ {{ number_format($sale->tax, 2) }}</span>
    </div>
    @if($sale->discount_amount > 0)
        <div class="flex justify-between text-gray-700">
            <span>Descuento:</span>
            <span>-RD$ {{ number_format($sale->discount_amount, 2) }}</span>
        </div>
    @endif
    <div class="flex justify-between font-black text-sm pt-1 border-t-dashed-black">
        <span>TOTAL:</span>
        <span>RD$ {{ number_format($sale->amount, 2) }}</span>
    </div>
</div>

@if(count($creditNote) > 0)
    <div class="text-10px border-t-dashed-black p-2 mt-3">
        <p>Notas De Cre. Aplicadas :</p>
        @foreach($creditNote as $item)
            <p><strong>*</strong> {{$item->code}}</p>
        @endforeach
    </div>
@endif

<div class="border-t-dashed-black my-3"></div>
<div class="flex justify-center w-full my-2">
    {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeSVG($sale->code, 'C128', 2.1, 72) !!}
</div>

<div class="border-t-dashed-black my-3"></div>

<div class="text-center text-10px space-y-1">
    <p class="font-bold uppercase">¡Gracias por preferirnos!</p>
    <p>Las cuentas abiertas/repuestos están sujetas a revisión técnica.</p>
    <p class="text-8px text-gray-600 mt-2">Impreso desde GG APP v2.0</p>

    <div style="height: 15mm;"></div>
    <span class="text-white text-10px" style="user-select: none;">.</span>
</div>

</body>

</html>

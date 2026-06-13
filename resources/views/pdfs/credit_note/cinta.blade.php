@php use App\Models\CreditNoteItem; @endphp
    <!DOCTYPE html>
<html lang="es">

<head>
    @php
        /** @var App\Models\CreditNote $cr */
        /** @var App\Models\Setting $setting */
    @endphp
    <meta charset="UTF-8">
    <title>Nota de Credito {{ $cr->code }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Configuraciones específicas de impresión que Tailwind no maneja por CDN */
        @page {
            size: 80mm auto;
            /* Alto dinámico infinito */
            margin: 0;
        }

        /* Forzar fuente monoespaciada tipo ticket de caja */
        body {
            font-family: 'Courier New', Courier, monospace;
        }

        /* Ocultar elementos innecesarios al imprimir (por si se previsualiza en navegador) */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

    <script>
        // Configuración personalizada de Tailwind por si quieres agregar algo específico
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        thermal: '#000000',
                    }
                }
            }
        }
    </script>

</head>

<body class="bg-white text-black antialiased px-2 pt-4 pb-0 mx-auto" style="width: 72mm;">

<div class="text-center mb-4">
    <h1 class="text-xl font-black tracking-wide uppercase">{{ $setting->name }}</h1>
    <p class="text-xs leading-relaxed mt-1">
        RNC: {{ $setting->company_id }}<br>
        Tel: {{ $setting->phone }}<br>
        Puerto Plata, Rep. Dominicana
    </p>
</div>

<div class="border-t border-dashed border-black my-2"></div>

<div class="text-xs space-y-0.5 mb-2">
    <div class="flex justify-between">
        <span class="font-bold">Factura:</span>
        <span>{{ $cr->code }}</span>
    </div>
    @if ($cr->ncf)
        <div class="flex justify-between">
            <span class="font-bold">NCF:</span>
            <span>{{ $cr->ncf ?? 'N/A' }}</span>
        </div>
    @endif

    <div class="flex justify-between">
        <span class="font-bold">FECHA:</span>
        <span>{{ $cr->created_at }}</span>
    </div>
    <div class="flex justify-between">
        <span class="font-bold">CLIENTE:</span>
        <span class="text-right truncate max-w-[45mm]">{{ $cr->client_name }}</span>
    </div>
</div>

<div class="border-t border-dashed border-black my-2"></div>

<table class="w-full text-xs border-collapse">
    <thead>
    <tr class="border-b border-dashed border-black font-bold">
        <th class="text-left py-1 w-[55%]">DESCRIPCIÓN</th>
        <th class="text-center py-1 w-[15%]">CANT</th>
        <th class="text-right py-1 w-[30%]">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @php
        /** @var CreditNoteItem $item */
    @endphp
    @foreach($cr->items as $item)
        <tr class="font-bold">
            <td class="pt-2 text-left uppercase break-words">
                <p>{{ $item->product->code }}</p>
                <p>{{ Str::limit($item->product->name, 15) }}</p>
            </td>
            <td class="pt-2 text-center">{{ number_format($item->quantity, 2) }}</td>
            <td class="pt-2 text-right">{{ number_format($item->amount, 2) }}</td>
        </tr>
        <tr class="text-[10px] text-gray-700 border-b border-dotted border-gray-400">
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

<div class="border-t border-dashed border-black my-2"></div>

<div class="w-[85%] ml-auto text-xs space-y-1">
    <div class="flex justify-between">
        <span>Sub-Total:</span>
        <span>RD$ {{ number_format($cr->sub_total, 2) }}</span>
    </div>
    <div class="flex justify-between">
        <span>ITBIS (18%):</span>
        <span>RD$ {{ number_format($cr->tax, 2) }}</span>
    </div>
    @if($cr->discount_amount > 0)
        <div class="flex justify-between text-gray-700">
            <span>Descuento:</span>
            <span>-RD$ {{ number_format($cr->discount_amount, 2) }}</span>
        </div>
    @endif
    <div class="flex justify-between font-black text-sm pt-1 border-t border-dashed border-black">
        <span>TOTAL:</span>
        <span>RD$ {{ number_format($cr->amount, 2) }}</span>
    </div>
</div>

<div class="border-t border-dashed border-black my-3"></div>
<div class="flex justify-center w-full my-2">
    {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeSVG($cr->code, 'C128', 2.1, 72) !!}
</div>

<div class="border-t border-dashed border-black my-3"></div>
<div class="text-[10px]">
    <p><strong>Comentario :</strong> </p>
    <p>
        {{$cr->comment}}
    </p>
</div>
<div class="border-t border-dashed border-black my-3"></div>

<div class="text-center text-[10px] space-y-1">
    <p class="font-bold uppercase">¡Gracias por preferirnos!</p>
    <p class="font-semibold">Política de Devoluciones:</p>
    <p>Las devoluciones deben ser solicitadas dentro de los próximos <span class="font-bold">15 días</span> posteriores
        a la compra.</p>
    <p>Es <span class="font-bold">OBLIGATORIO</span> presentar la factura original para procesar la devolución.</p>
    <p class="text-[8px] text-gray-500 mt-1">Aplica condiciones y restricciones. Productos deben estar en su empaque
        original.</p>

    <p class="text-[8px] text-gray-600 mt-2">Impreso desde Repuesto Camboya SRL</p>

    <div class="h-[15mm]"></div>
    <span class="text-white text-[1px] select-none">.</span>
</div>

</body>

</html>

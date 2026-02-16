<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura #001234</title>
    <style>
        .container{
            margin-top: 55mm;
        }
        table{
            width: 100%;
        }
        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5mm;
        }

        /* Encabezado de tabla */
        table thead {
            background: #2563eb;
            color: white;
        }

        table thead tr th {
            padding: 3mm 2mm;
            text-align: left;
            font-size: 10pt;
            font-weight: 600;
            border-bottom: 2px solid #1e40af;
        }

        /* Alineación de columnas específicas */
        table thead tr th.text-center {
            text-align: center;
        }

        table thead tr th.text-right {
            text-align: right;
        }

        /* Cuerpo de tabla */
        table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }


        table tbody tr td {
            padding: 2mm 2mm;
            font-size: 9pt;
            color: #374151;
        }

        /* Alineación de celdas */
        table tbody tr td.text-center {
            text-align: center;
        }

        table tbody tr td.text-right {
            text-align: right;
        }

        table tbody tr td.text-bold {
            font-weight: 600;
        }

        /* Footer de tabla (totales) */
        table tfoot {
            background: #f3f4f6;
            border-top: 2px solid #2563eb;
        }

        table tfoot tr td {
            padding: 3mm 2mm;
            font-weight: 600;
            font-size: 11pt;
        }


    </style>
</head>
<body class="bg-gray-50 p-8  ">
@php
    /** @var App\Models\PurchaseReceipts $receipts */
    /** @var App\Models\PurchaseReceiptsItem $item */
@endphp
<div class="container w-full">
    <!-- Header -->

    <!-- Items Table -->
    <div class="main-box">
        <table >
            <thead>
            <tr >
                <th >Descripción</th>
                <th >Can. Exp. / Cant. Rec.</th>
                <th >Costo</th>
                <th >Itbis</th>
                <th >Descuento</th>
                <th >Importe</th>
            </tr>
            </thead>
            <tbody >
            @foreach($receipts->items as $item)
                <tr>
                    <td>{{$item->product->name}}</td>
                    <td>{{$item->quantity_expected}} / {{$item->quantity_received}}</td>
                    <td>{{$item->cost}}</td>
                    <td>{{$item->tax_amount}}</td>
                    <td>{{$item->discount}}</td>
                    <td>{{$item->amount}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>




</div>
</body>
</html>

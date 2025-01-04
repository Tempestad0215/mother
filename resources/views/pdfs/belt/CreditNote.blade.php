<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Devolución</title>
</head>
<body class="max-w-[80mm]">

{{--  Logo de la empresa  --}}
{{--    <img--}}
{{--        class="max-w-[5rem] mx-auto rounded-md"--}}
{{--        src="{{public_path('logo.jpeg')}}" alt="">--}}
{{--  Informacion de la empresa   --}}
<h3 class="text-xl text-center">{{$setting->name}}</h3>
<div class="text-xs text-center border-b ">
    <p>{{$setting->email}}</p>
    <p>{{$setting->phone}}</p>
    <p>{{$setting->address}}</p>
</div>
{{--Datos de la factura--}}

{{--Datos de la factura--}}
<div class="text-xs mt-2">
    {{--    Fecha de documento    --}}
    <p>
        Fecha Documento:
        <span>{{$creditNote->created_at}}</span>
    </p>
    {{--    Si existe    --}}
    <p >
        Cliente:
        <span>{{$creditNote->client_name}}</span>
    </p>
    {{--    Si existe    --}}
    @if( isset($creditNote->client_rnc))
        <p>
            RNC:
            <span>{{$creditNote->client_rnc}}</span>
        </p>
    @endif
</div>

{{--Titulo de la factura--}}
<h3 class="border-b border-t font-bold text-center ">
    Devolución
</h3>

{{--    Datos del productos--}}
<table
    class="w-full table-fixed mt-2">
    <thead>
    <tr
        class="border-t border-b ">
        <th class="w-[40mm] max-w-[40mm]">Descripción</th>
        <th class="w-[28mm] max-w-[18mm]" >Itbis</th>
        <th class="w-[18mm] max-w-[18mm]" >Valor</th>
    </tr>
    </thead>
    <tbody class="text-xs">
    @foreach($creditNote->trans as $products )
        <tr class="border-b ">
            <td>
                <p>{{$products->stock}} x {{number_format($products->price,2)}}</p>
                <p>{{$products->product_name}}</p>
            </td>
            <td>
                {{ number_format($products->tax,2)}}
            </td>
            <td>
                {{ number_format($products->amount,2)}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{--Datos de totales--}}
<table class=" mt-3 table-auto w-full text-sm  ">
    <tbody>
    <tr>
        <th class="text-right">Itbis :</th>
        <td>{{number_format($creditNote->tax,2)}}</td>
    </tr>
    <tr>
        <th class="text-right">Sub Total :</th>
        <td>{{number_format($creditNote->sub_total,2)}}</td>
    </tr>
    <tr>
        <th class="text-right">Decuento :</th>
        <td>{{number_format($creditNote->discount_amount,2)}}</td>
    </tr>
    <tr>
        <th class="text-right">Total :</th>
        <td class="" >{{ number_format($creditNote->amount,2)}}</td>
    </tr>
    </tbody>
</table>


{{--    Comentario y datos finales--}}
<div class="text-sm mt-3 border-t ">
    <p>
            <span class="font-bold">
                Nota Importante:
            </span>
        Las piezas eléctricas y aquellas instaladas fuera de nuestro taller no cuentan con garantía. Asegúrese de verificar la compatibilidad y el estado de los productos antes de proceder con su instalación.
    </p>
</div>

{{--    Informaciond el comentario--}}
<div class="text-sm mt-3 border-t border-b ">
    <p>
        <span class="font-bold">Comentario:</span>
        {{$creditNote->comment->content}}
    </p>
</div>

{{--Codigo de barra--}}
<div class="mt-3 text-center">
    <div class="relative pl-[13%]">
        {!! DNS1D::getBarcodeHtml($creditNote->code ,'C128') !!}
    </div>

    <span>* {{$creditNote->code}} *</span>
</div>


<div class="border-t  text-sm">
{{--    <p>Le Atendió : {{$creditNote->audits[0]->user->name}} </p>--}}
    <p>Impresión : {{$datePrint}}</p>
</div>

</body>
</html>


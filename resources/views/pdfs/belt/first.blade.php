<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body class="max-w-[80mm]">

{{--  Logo de la empresa  --}}
{{--    <img--}}
{{--        class="max-w-[5rem] mx-auto rounded-md"--}}
{{--        src="{{public_path('logo.jpeg')}}" alt="">--}}
{{--  Informacion de la empresa   --}}
    <h3 class="text-xl text-center">{{$setting->name}}</h3>
    <div class="text-xs text-center border-b border-black">
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
            <span>{{$sale->created_at}}</span>
        </p>
{{--    Si existe    --}}
        <p >
            Cliente:
            <span>{{$sale->client_name}}</span>
        </p>
        {{--    Si existe    --}}
        @if( isset($sale->client_rnc))
            <p>
                RNC:
                <span>{{$sale->client_rnc}}</span>
            </p>
        @endif
    </div>

{{--    Datos del productos--}}
    <table
        class="w-full table-fixed mt-2">
        <thead>
            <tr
                class="border-t border-b border-black">
                <th class="w-[40mm] max-w-[40mm]">Descripción</th>
                <th class="w-[28mm] max-w-[18mm]" >Itbis</th>
                <th class="w-[18mm] max-w-[18mm]" >Valor</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach($sale->infoSale->where('type','VENTAS') as $products )
                <tr class="border-b border-black">
                    <td class="truncate text-xs">

                        <p class="">
                            {{$products->stock}} x {{number_format($products->price,2)}} |
                            {{$products->code}}
                        </p>
                        <p>{{$products->product_name}}</p>
                    </td>
                    <td class="px-3">
                        {{ number_format($products->tax,2)}}
                    </td>
                    <td class="px-3" >
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
            <td>{{number_format($sale->tax,2)}}</td>
        </tr>
        <tr>
            <th class="text-right">Sub Total :</th>
            <td>{{number_format($sale->sub_total,2)}}</td>
        </tr>
        <tr>
            <th class="text-right">Decuento :</th>
            <td>{{number_format($sale->discount_amount,2)}}</td>
        </tr>
        <tr>
            <th class="text-right">Total :</th>
            <td class="" >{{ number_format($sale->amount,2)}}</td>
        </tr>
        </tbody>
    </table>


    {{--Informacion de pago y devuelta--}}
    <h3 class="border-t border-b border-black text-center">
        Información de pago
    </h3>
    <div class="text-sm">
        <p> <strong>Tipo de pago : </strong> {{$sale->type_payment->name}}</p>
        <p> <strong>Pagó con :</strong> {{number_format($sale->received,2)}}</p>
        <p> <strong>Devuelta :</strong> {{number_format($sale->returned,2)}}</p>
    </div>

{{--    Comentario y datos finales--}}
    <div class="text-sm mt-3 border-t border-black">
        <p>
            <span class="font-bold">
                Nota Importante:
            </span>
            Las piezas eléctricas y aquellas instaladas fuera de nuestro taller no cuentan con garantía. Asegúrese de verificar la compatibilidad y el estado de los productos antes de proceder con su instalación.
        </p>
    </div>

{{--    Informaciond el comentario--}}
    <div class="text-sm mt-3 border-t border-b border-black">
        <p>
            <span class="font-bold">Comentario:</span>
            @if($sale->comment)
                {{$sale->comment->content}}
            @endif

        </p>
    </div>

{{--Codigo de barra--}}
    <div class=" text-center mt-3">
        <div class="pl-[10%]">
            {!! DNS1D::getBarcodeHtml($sale->code ,'C128') !!}
        </div>

        <spa>* {{$sale->code}} *</spa>
    </div>


    <div class="border-t border-black text-sm">
        <p>Le Atendió : {{$sale->audits[0]->user->name}} </p>
        <p>Impresión : {{$datePrint}}</p>
    </div>

</body>
</html>


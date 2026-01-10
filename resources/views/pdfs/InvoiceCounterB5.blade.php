<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Conteo {{$counter->created_at}}</title>
</head>



{{--Contenido de la vnetnaa--}}
<body>
    <h3 class="text-xl text-center">{{$setting->name}}</h3>
    <div class="text-xs text-center border-b border-black">
        <p>{{$setting->email}}</p>
        <p>{{$setting->phone}}</p>
        <p>{{$setting->address}}</p>
    </div>
    <h3 class="font-bold text-sm text-center border-b border-black" >Conteo {{$counter->created_at}}</h3>
    <table class="table-auto w-full text-sm border-collapse border">
        <caption>Ingresos</caption>
        <thead>
            <tr>
                <th class="border px-2">Tipo</th>
                <th class="border px-2">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-2">Moneda de 1</td>
                <td class="border px-2">RD: {{number_format($counter->coin_first,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Moneda de 5</td>
                <td class="border px-2">RD: {{number_format($counter->coin_second,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Moneda de 10</td>
                <td class="border px-2">RD: {{number_format($counter->coin_third,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Moneda de 25</td>
                <td class="border px-2">RD: {{number_format($counter->coin_fourth,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Papeleta de 50</td>
                <td class="border px-2">RD: {{number_format($counter->coin_fifth,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Papeleta de 100</td>
                <td class="border px-2">RD: {{number_format($counter->coin_sixth,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Papeleta de 200</td>
                <td class="border px-2">RD: {{number_format($counter->coin_seventh,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Papeleta de 500</td>
                <td class="border px-2">RD: {{number_format($counter->coin_eighth,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Papeleta de 1,000</td>
                <td class="border px-2">RD: {{number_format($counter->coin_ninth,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Papeleta de 2,000</td>
                <td class="border px-2">RD: {{number_format($counter->coin_tenth,2)}}</td>
            </tr>
        </tbody>
    </table>
    <table
        class="table-auto w-full text-sm border-collapse border">
        <caption>Otros Ingresos</caption>
        <thead>
            <tr>
                <th class="border px-2">Tipo</th>
                <th class="border px-2">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-2">Tajeta</td>
                <td class="border px-2">RD: {{number_format($counter->card,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Transferencia</td>
                <td class="border px-2">RD: {{number_format($counter->transfer,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Cheque</td>
                <td class="border px-2">RD: {{number_format($counter->check,2)}}</td>
            </tr>
            <tr>
                <td class="border px-2">Otros</td>
                <td class="border px-2">RD: {{number_format($counter->otder_income,2)}}</td>
            </tr>
        </tbody>
    </table>
    <table
        class="table-auto w-full text-sm border-collapse border">
        <caption>Gastos</caption>
        <thead>
        <tr>
            <th class="border px-2">Tipo</th>
            <th class="border px-2">Cantidad</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="border px-2">Gastos</td>
            <td class="border px-2">RD: {{number_format($counter->expenses,2)}}</td>
        </tr>
        <tr>
            <td class="border px-2">Retiro Caja</td>
            <td class="border px-2">RD: {{number_format($counter->cash_withdrawals,2)}}</td>
        </tr>
        <tr>
            <td class="border px-2">Devoluciones</td>
            <td class="border px-2">RD: {{number_format($counter->refund,2)}}</td>
        </tr>
        <tr>
            <td class="border px-2">Otros</td>
            <td class="border px-2">RD: {{number_format($counter->other_expenses,2)}}</td>
        </tr>
        </tbody>
    </table>


    <table class="table-auto w-full">
        <caption>Resultado</caption>
        <tbod>
            <tr>
                <th>Ingreso Total</th>
                <td>RD: {{number_format($counter->total_coin,2)}}</td>
            </tr>
            <tr>
                <th>Otros Total</th>
                <td>RD: {{number_format($counter->total_other_coin,2)}}</td>
            </tr>
            <tr>
                <th>Gastos Total</th>
                <td>RD: {{number_format($counter->total_expenses,2)}}</td>
            </tr>
            <tr>
                <th>Diferencias</th>
                <td>RD: {{number_format($counter->diff,2)}}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td>RD: {{number_format($counter->total_neto,2)}}</td>
            </tr>
        </tbod>
    </table>

</body>
</html>

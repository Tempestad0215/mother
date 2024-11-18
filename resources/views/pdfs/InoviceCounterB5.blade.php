<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Report</title>
</head>

{{--Estilo de la ventnaa--}}
<style>
    th{
        border-bottom: 1px solid black;
        text-align: center;
    }
    tbody td{
        padding: 20px;
        border-bottom: 1px solid black;
    }


</style>


{{--Contenido de la vnetnaa--}}
<body>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Moneda de 1</td>
                <td>{{$counterMoney->coin_first}}</td>
            </tr>
            <tr>
                <td>Moneda de 5</td>
                <td>{{$counterMoney->coin_second}}</td>
            </tr>
            <tr>
                <td>Moneda de 10</td>
                <td>{{$counterMoney->coin_third}}</td>
            </tr>
            <tr>
                <td>Moneda de 25</td>
                <td>{{$counterMoney->coin_fourth}}</td>
            </tr>
            <tr>
                <td>Papeleta de 50</td>
                <td>{{$counterMoney->coin_fifth}}</td>
            </tr>
            <tr>
                <td>Papeleta de 100</td>
                <td>{{$counterMoney->coin_sixth}}</td>
            </tr>
            <tr>
                <td>Papeleta de 200</td>
                <td>{{$counterMoney->coin_seventh}}</td>
            </tr>
            <tr>
                <td>Papeleta de 500</td>
                <td>{{$counterMoney->coin_eighth}}</td>
            </tr>
            <tr>
                <td>Papeleta de 1,000</td>
                <td>{{$counterMoney->coin_ninth}}</td>
            </tr>
            <tr>
                <td>Papeleta de 2,000</td>
                <td>{{$counterMoney->coin_tenth}}</td>
            </tr>


        </tbody>
    </table>
</body>
</html>

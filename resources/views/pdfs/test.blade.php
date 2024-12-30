<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body>
    <div class="bg-red-400 text-center text-red-700">
        esta contendioo des de la prueba de todo los tiempos
    </div>

    <table class="table-auto w-full">
        <tr>
            <th>#</th>
            <th>name</th>
            <th>test</th>
        </tr>
        @for($i = 0; $i < 10; $i++ )
            <tr class="even:bg-gray-50">
                <td>
                    {{$i}}
                </td>
                <td>
                    name{{$i}}
                </td>
                <td>
                    test{{$i}}
                </td>
            </tr>
        @endfor
    </table>

    <div class="text-right">
        aSDASDASDASDASDASDASD
    </div>
</body>
</html>


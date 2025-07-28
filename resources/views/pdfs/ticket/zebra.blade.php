<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiqueta de Impresión</title>
    <style>
        @page  {
            max-height: 30mm;
            max-width: 60mm;
        }
        body{
            margin: 0;
            padding: 0;
        }
        .title{
            margin: 0;
            padding: 0;
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            font-family: monospace, monospace;
        }
    </style>
</head>
<body class=" font-mono">
<div class="">
    <div class="title">
        {{ $name }}
    </div>
    <p class="text-center">
        {{$ref}}
    </p>
{{--    <p>--}}
{{--        {{$code_bar}}--}}
{{--    </p>--}}


    <img src="{{$code_bar}}" alt="">
</div>
</body>

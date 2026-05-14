<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiqueta de Impresión</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class=" font-mono">
<div class="text-center text-[10px]">
    <div class="title ">
        {{ $name }}
    </div>
    <p class="text-center">
        {{$ref}}
    </p>
{{--    <p>--}}
{{--        {{$code_bar}}--}}
{{--    </p>--}}
    <div class="flex justify-center items-center">
        <svg id="barcode"></svg>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.12.3/dist/barcodes/JsBarcode.code128.min.js"></script>
<script>
    import JsBarcode from "jsbarcode";

    JsBarcode("#barcode", "{{ $code_bar }}", {
        format: "CODE128",
        width: 1.5,
        height: 35,
        displayValue: true,
        margin: 0,
        fontSize: 10
    });
</script>
</body>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Label Printer</title>
</head>
<body>
    <div class=" ">

        {{-- ARREGLADO: 1.5 de ancho y 50 de alto --}}
        {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeSVG($code, 'C128', 3.2, 120) !!}
    </div>

</body>
</html>

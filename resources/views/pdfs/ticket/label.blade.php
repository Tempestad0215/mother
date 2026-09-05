<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Label Printer</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .label-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px; /* Espacio entre elementos */
            text-align: center;
        }
        .app-name {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #333333;
        }
        .barcode-wrapper {
            width: auto;
        }
        .code-text {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1e293b;
        }
    </style>
</head>
<body>
<div class="label-container">
    <!-- Nombre de la aplicación (Texto superior) -->
    <span class="app-name">{{ config('app.name') }}</span>

    <!-- SVG del código de barras -->
    <div class="barcode-wrapper">
        {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeSVG($code, 'C128', 3.2, 110, 'black', false) !!}
    </div>

    <!-- Texto inferior con el código aumentado y estilizado -->
    <span class="code-text">
            {{ $code }}
        </span>
</div>
</body>
</html>

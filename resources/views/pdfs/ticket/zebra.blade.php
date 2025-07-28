<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiqueta de Impresión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.code128.min.js"></script>
</head>
<body class="font-mono">
<div class="border border-gray-800 !max-w-[9.5cm] h-[4.4cm]">
    <div class="text-center text-xl font-bold">
        {{ $name }}
    </div>
    <p class="text-center">
        {{$ref}}
    </p>

    <svg class="w-full  mt-5" id="barcode"></svg>

</div>
<script>
	document.addEventListener("DOMContentLoaded", function () {
		JsBarcode("#barcode", "{{ $code_bar }}", {
			format: "CODE128",
			width: 2.4,
			height: 75,
			displayValue: true,
			fontSize: 16,
			margin: 5
		});
	})
</script>
</body>

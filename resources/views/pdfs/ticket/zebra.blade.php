<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiqueta de Impresión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-mono">

<div class="container mx-auto p-4">
    <div class="border border-gray-800 p-4 w-80">
        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo de la Empresa" class="h-16">
        </div>

        <div class="text-center text-xl font-bold mb-2">
            {{ $empresa->nombre }}
        </div>

        <div class="mb-2">
            <p class="text-lg">Producto: {{ $producto->nombre }}</p>
            <p class="text-base">Código: {{ $producto->codigo }}</p>
        </div>

        <div class="flex justify-center">
            {!! $codigo_de_barras !!}
        </div>

        <div class="text-xs mt-2">
            {{ $empresa->direccion }} - {{ $empresa->telefono }}
        </div>
    </div>
</div>

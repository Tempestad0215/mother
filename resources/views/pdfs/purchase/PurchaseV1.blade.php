{{-- ticket.blade.php --}}
    <!DOCTYPE html>
<html>
<head>
    <style>
        @media print {
            @page {
                margin: 0;
            }
            body { margin: 0;  max-width: 800mm}

            /* REPETIR HEADER EN CADA PÁGINA */
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }

            /* EVITAR CORTES */
            tr { page-break-inside: avoid; }
        }
        body{
            max-width: 800mm;
            background-color: blue;
        }
        .content-main{
            max-height: 100px;
            background: red;
        }
        .header {
            position: running(header);
        }
        .footer {
            position: running(footer);
        }

        @page {
            @top-center {
                content: element(header);
            }
            @bottom-center {
                content: element(footer);
            }
        }
    </style>
</head>
<body>
    {{-- HEADER QUE SE REPITE --}}
    <div class="header" style="position: fixed; top: 0;">
        <h1>Mi Empresa</h1>
        Página <span class="page-number"></span>
    </div>
    <div class="content-main">
        {{-- CONTENIDO --}}
        @foreach($items as $item)
            <div class="item">{{ $item }}</div>
        @endforeach

    </div>

    {{-- FOOTER QUE SE REPITE --}}
    <div class="footer" style="position: fixed; bottom: 0;">
        Fecha: {{ date('d/m/Y') }}
    </div>

    <script>
        // NUMERAR PÁGINAS
        document.querySelectorAll('.page-number').forEach((el, i) => {
            el.textContent = i + 1;
        });

        // AUTOIMPRIMIR
        window.onload = () => window.print();
    </script>
</body>
</html>

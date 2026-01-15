{{-- resources/views/pdf/purchase-invoice.blade.php --}}
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra #{{ 12536  }}</title>

    <style>
        /* Reset y base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            max-width: 21cm;
            margin: 0 auto;
            padding: 1.5cm;
        }

        /* Contenedor principal */
        .invoice-container {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: #fff;
        }

        /* Header con gradiente */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        .company-info h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .company-info p {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-title .badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
        }

        /* Información de compra */
        .purchase-info {
            padding: 25px 40px;
            background: #f8fafc;
            border-bottom: 1px solid #e9ecef;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-card {
            background: white;
            padding: 18px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .info-card h3 {
            font-size: 13px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card h3::before {
            content: '•';
            color: #667eea;
            font-size: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #f1f3f5;
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            font-weight: 500;
            color: #64748b;
        }

        .info-value {
            font-weight: 600;
            color: #334155;
        }

        /* Tabla de productos */
        .products-section {
            padding: 30px 40px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: #667eea;
            border-radius: 2px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .products-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
        }

        .products-table th {
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e9ecef;
        }

        .products-table tbody tr {
            border-bottom: 1px solid #f1f3f5;
            transition: background 0.2s;
        }

        .products-table tbody tr:hover {
            background: #f8fafc;
        }

        .products-table td {
            padding: 14px 12px;
            vertical-align: top;
        }

        .product-name {
            font-weight: 500;
            color: #334155;
        }

        .product-code {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .highlight {
            background: #f0f7ff !important;
            font-weight: 600;
        }

        /* Totales */
        .totals-section {
            padding: 0 40px 30px;
        }

        .totals-container {
            max-width: 400px;
            margin-left: auto;
            background: #f8fafc;
            border-radius: 10px;
            padding: 25px;
            border: 1px solid #e9ecef;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .total-row:last-child {
            border-bottom: none;
        }

        .total-label {
            font-size: 13px;
            color: #64748b;
        }

        .total-value {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .grand-total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 20px -25px -25px -25px;
            padding: 20px 25px;
            border-radius: 0 0 10px 10px;
            color: white;
        }

        .grand-total .total-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }

        .grand-total .total-value {
            color: white;
            font-size: 24px;
            font-weight: 700;
        }

        /* Términos y condiciones */
        .terms-section {
            padding: 25px 40px;
            background: #f8fafc;
            border-top: 1px solid #e9ecef;
            margin-top: 30px;
            border-radius: 0 0 12px 12px;
        }

        .terms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .terms-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .terms-card h4 {
            font-size: 13px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 12px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .terms-card p {
            color: #64748b;
            line-height: 1.6;
        }

        /* Firmas */
        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px dashed #e9ecef;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            height: 1px;
            background: #334155;
            margin: 40px 0 10px;
        }

        .signature-name {
            font-weight: 600;
            color: #334155;
            margin-top: 8px;
        }

        .signature-title {
            font-size: 11px;
            color: #64748b;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
        }

        .invoice-footer p {
            margin-bottom: 4px;
        }

        /* Utilidades */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .currency {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }

        /* Media print */
        @media print {
            body {
                padding: 0;
                max-width: 100%;
            }

            .invoice-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .no-print {
                display: none;
            }
        }

    </style>

</head>
<body>
<div class="invoice-container fade-in">
    <!-- Header -->
    <div class="invoice-header">
        <div class="header-content">
            <div class="company-info">
                <h1>{{ config('company.name', 'Mi Empresa S.A.') }}</h1>
                <p><i class="fas fa-map-marker-alt"></i> {{ config('company.address', 'Av. Principal #123, Ciudad') }}</p>
                <p><i class="fas fa-phone"></i> {{ config('company.phone', '+1 (555) 123-4567') }}</p>
                <p><i class="fas fa-envelope"></i> {{ config('company.email', 'info@empresa.com') }}</p>
                <p><i class="fas fa-globe"></i> {{ config('company.website', 'www.empresa.com') }}</p>
            </div>

            <div class="invoice-title">
                <h2>Orden de Compra</h2>
                <div class="badge">
                    <i class="fas fa-file-invoice"></i> Documento Oficial
                </div>
                <p style="margin-top: 15px; opacity: 0.9;">
                    <i class="fas fa-hashtag"></i> {{ 12556564 }}
                </p>
            </div>
        </div>
    </div>

    <!-- Información de compra -->
    <div class="purchase-info">
        <div class="info-grid">
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Información General</h3>
                <div class="info-item">
                    <span class="info-label">Número:</span>
                    <span class="info-value">{{ 54665 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha Emisión:</span>
                    <span class="info-value">{{ 4545 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha Entrega:</span>
                    <span class="info-value">{{ 5445 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Estado:</span>
                    <span class="info-value">
                            <span class="status-badge {{ 544566 }}">
                                {{ 655654 }}
                            </span>
                        </span>
                </div>
            </div>

            <div class="info-card">
                <h3><i class="fas fa-user-tie"></i> Proveedor</h3>
                <div class="info-item">
                    <span class="info-label">Razón Social:</span>
                    <span class="info-value">{{ 54564 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">RUC/RFC:</span>
                    <span class="info-value">{{46565 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contacto:</span>
                    <span class="info-value">{{ 45456456}}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ 544456 }}</span>
                </div>
            </div>

            <div class="info-card">
                <h3><i class="fas fa-building"></i> Empresa</h3>
                <div class="info-item">
                    <span class="info-label">Solicitante:</span>
                    <span class="info-value">{{54456 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Departamento:</span>
                    <span class="info-value">{{ 5644 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Centro Costo:</span>
                    <span class="info-value">{{ 654645 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Almacén:</span>
                    <span class="info-value">{{ 546544 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="products-section">
        <div class="section-title">
            <i class="fas fa-boxes"></i> Productos Solicitados
        </div>

        <table class="products-table">
            <thead>
            <tr>
                <th width="5%">#</th>
                <th width="40%">Descripción del Producto</th>
                <th width="10%">Código</th>
                <th width="10%" class="text-center">Cantidad</th>
                <th width="15%" class="text-right">Precio Unitario</th>
                <th width="10%" class="text-right">Descuento</th>
                <th width="10%" class="text-right">Total</th>
            </tr>
            </thead>
            <tbody>
{{--            @foreach($order->items as $index => $item)--}}
{{--                <tr class="{{ $index % 2 == 0 ? 'highlight' : '' }}">--}}
{{--                    <td class="text-center">{{ $index + 1 }}</td>--}}
{{--                    <td>--}}
{{--                        <div class="product-name">{{ $item->product->name ?? 'Producto ' . ($index + 1) }}</div>--}}
{{--                        @if($item->product->description ?? false)--}}
{{--                            <div class="product-code">{{ Str::limit($item->product->description, 80) }}</div>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td>{{ $item->product->code ?? 'PRD-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>--}}
{{--                    <td class="text-center">{{ number_format($item->quantity, 2) }} {{ $item->unit ?? 'UND' }}</td>--}}
{{--                    <td class="text-right currency">${{ number_format($item->unit_price, 2) }}</td>--}}
{{--                    <td class="text-right currency">${{ number_format($item->discount_amount, 2) }}</td>--}}
{{--                    <td class="text-right currency"><strong>${{ number_format($item->total, 2) }}</strong></td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}

            <!-- Espacio para más productos -->
{{--            @for($i = count($order->items); $i < 8; $i++)--}}
{{--                <tr style="height: 40px;">--}}
{{--                    <td colspan="7"></td>--}}
{{--                </tr>--}}
{{--            @endfor--}}
            </tbody>
        </table>
    </div>

    <!-- Totales -->
    <div class="totals-section">
        <div class="totals-container">
            <div class="total-row">
                <span class="total-label">Subtotal:</span>
                <span class="total-value currency">${{ 44665 }}</span>
            </div>

            <div class="total-row">
                <span class="total-label">Descuento General:</span>
                <span class="total-value currency">-${{ 44456 }}</span>
            </div>

            <div class="total-row">
                <span class="total-label">IVA (16%):</span>
                <span class="total-value currency">${{ 444564}}</span>
            </div>

            <div class="total-row">
                <span class="total-label">Fletes y Seguros:</span>
                <span class="total-value currency">${{ 4556465 }}</span>
            </div>

            <div class="total-row">
                <span class="total-label">Otros Cargos:</span>
                <span class="total-value currency">${{ 5465 }}</span>
            </div>

            <div class="grand-total">
                <div class="total-row">
                    <span class="total-label">TOTAL ORDEN:</span>
                    <span class="total-value currency">${{ 56445 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Términos y condiciones -->
    <div class="terms-section">
        <div class="terms-grid">
            <div class="terms-card">
                <h4><i class="fas fa-file-contract"></i> Condiciones de Pago</h4>
                <p>{{ 556464 }}</p>
            </div>

            <div class="terms-card">
                <h4><i class="fas fa-truck"></i> Términos de Entrega</h4>
                <p>{{ 6+45664645 }}</p>
            </div>

            <div class="terms-card">
                <h4><i class="fas fa-sticky-note"></i> Observaciones</h4>
                <p>{{ 56464654 }}</p>
            </div>
        </div>
    </div>

    <!-- Firmas -->
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ 454564 }}</div>
            <div class="signature-title">Solicitante / Autorizado</div>
        </div>

        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ 654645 }}</div>
            <div class="signature-title">Aprobado por</div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="invoice-footer">
    <p><i class="fas fa-print"></i> Documento generado electrónicamente - {{ now()->format('d/m/Y H:i:s') }}</p>
    <p>Página 1 de 1 • {{ config('app.name', 'Sistema de Compras') }} v{{ config('app.version', '1.0') }}</p>
    <p class="no-print">Este es un documento oficial. Conservar para fines de auditoría.</p>
</div>

</body>
</html>

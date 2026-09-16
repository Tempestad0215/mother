<div>
    <style>
        .ticket-body {
            width: 100%;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
        }

        .section-header {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin: 6px 0 4px 0;
        }

        .ticket-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ticket-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .divider-dashed {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .divider-double {
            border-top: 3px double #000;
            margin: 6px 0;
        }

    </style>

    <div class="ticket-body">
        {{-- Encabezado Reutilizable --}}
        <x-ticket-header
            document-type="Cierre de Caja"
            :document-code="$code"
            :document-date="$cashRegister->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i')"
            :setting="$setting"
        />

        {{-- SECCIÓN: EFECTIVO EN GAVETA --}}
        <div class="section-header">ARQUEO DE EFECTIVO</div>

        <table class="ticket-table">
            <tr>
                <td class="text-left">(+) Fondo de Apertura:</td>
                <td class="text-right">{{ number_format((float)data_get($dataSummary, 'opening_fund',0), 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">(+) Ventas Efectivo (Contado):</td>
                <td class="text-right">{{ number_format((float)data_get($dataSummary, 'cash_sales',0), 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">(+) Entradas Manuales (Income):</td>
                <td class="text-right">{{ number_format((float)data_get($dataSummary, 'manual_incomes',0), 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">(-) Gastos de Caja:</td>
                <td class="text-right">-{{ number_format((float)data_get($dataSummary, 'cash_expenses',0), 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">(-) Entregas a Bóveda:</td>
                <td class="text-right">-{{ number_format((float)data_get($dataSummary, 'vault_deliveries',0), 2) }}</td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        <table class="ticket-table font-bold">
            <tr>
                <td class="text-left">Efectivo Esperado:</td>
                <td class="text-right">{{ number_format((float)$cashRegister->expected_balance, 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">Efectivo Físico Contado:</td>
                <td class="text-right">{{ number_format((float)$cashRegister->closing_balance, 2) }}</td>
            </tr>
            @php
                $diferencia =  (float)bcsub((string)$cashRegister->expected_balance, (string)$cashRegister->expected_balance);
            @endphp
            <tr>
                <td class="text-left">Diferencia:</td>
                <td class="text-right">{{ number_format($diferencia, 2) }}</td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        {{-- SECCIÓN: TOTALES DE CONTROL (NO EFECTIVO) --}}
        <div class="section-header">TOTALES NO EFECTIVO</div>

        <table class="ticket-table">
            <tr>
                <td class="text-left">Tarjetas:</td>
                <td class="text-right">{{ number_format((float)data_get($dataSummary, 'card_total',0), 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">Transferencias:</td>
                <td class="text-right">{{ number_format((float)data_get($dataSummary, 'transfer_total',0), 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">Créditos Emitidos:</td>
                <td class="text-right">{{ number_format((float)data_get($dataSummary, 'credit_total',0), 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">Cheques:</td>
                <td class="text-right">{{ number_format((float)data_get($dataSummary, 'cheque_total',0), 2) }}</td>
            </tr>
        </table>

        <div class="divider-double"></div>

        {{-- FIRMA Y RESPONSABLE --}}
        <div style="margin-top: 35px; text-align: center;">
            <p>__________________________________</p>
            <p style="margin-top: 3px;" class="font-bold">Firma del Cajero</p>
            <p style="font-size: 11px;">Cajero: {{\Illuminate\Support\Facades\Auth::user()->name}}</p>
        </div>
    </div>
</div>

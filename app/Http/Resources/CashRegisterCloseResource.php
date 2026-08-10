<?php

namespace App\Http\Resources;

use App\Enums\CashMovementType;
use App\Enums\PaymentTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Models\CashMovement;
use App\Models\CashRegister;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property-read Collection<int, Sale> $sales
 * @property-read Collection<int, CashMovement> $movements
 * @mixin CashRegister MSV4A1A-L1R
 */
class CashRegisterCloseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $totalExpense = '0.0000';
        $totalVaultDeposit = '0.0000';
        $totalIncome = '0.0000';
        $totalInitialFund = '0.0000';

        // 🔄 Procesamos todos los movimientos de la caja
        foreach ($this->movements as $movement) {
            match ($movement->type) {
                CashMovementType::EXPENSE =>
                $totalExpense = bcadd($totalExpense, (string)$movement->amount, 4),

                CashMovementType::VAULT_DEPOSIT =>
                $totalVaultDeposit = bcadd($totalVaultDeposit, (string)$movement->amount, 4),

                CashMovementType::INITIAL_FUND =>
                $totalInitialFund = bcadd($totalInitialFund, (string)$movement->amount, 4),

                CashMovementType::INCOME =>
                $totalIncome = bcadd($totalIncome, (string)$movement->amount, 4),
            };
        }

        $totalContado = '0.0000';
        $totalCredito = '0.0000';
        $totalTransferencia = '0.0000';
        $totalCheque = '0.0000';
        $totalAnticipo = '0.0000';
        $totalTarjeta = '0.0000';

        // Para poder calcular el dinero esperado en la gaveta, necesitamos sumar las ventas
        foreach ($this->sales as $sale) {

            // 1. Si el tipo de venta es Cotización, saltamos esta iteración
            if ($sale->type === SaleTypeEnum::Cotizacion) {
                continue; // Continúa con la siguiente venta del foreach
            }else{
                match ($sale->type_payment){
                    PaymentTypeEnum::CONTADO => $totalContado = bcadd($totalContado, (string)$sale->amount, 4),
                    PaymentTypeEnum::CREDITO => $totalCredito = bcadd($totalCredito, (string)$sale->amount, 4),
                    PaymentTypeEnum::TRANSFERENCIA => $totalTransferencia = bcadd($totalTransferencia, (string)$sale->amount, 4),
                    PaymentTypeEnum::Cheque => $totalCheque = bcadd($totalCheque, (string)$sale->amount, 4),
                    PaymentTypeEnum::ANTICIPO => $totalAnticipo = bcadd($totalAnticipo, (string)$sale->amount, 4),
                    PaymentTypeEnum::TARJETA => $totalTarjeta = bcadd($totalTarjeta, (string)$sale->amount, 4)
                };
            }


        }

        // 🧮 Operación Matemática Corregida para el Dinero Esperado en Gaveta:
        // (Apertura + Fondo Inicial + Ingresos) - Gastos

        // 🧮 Operación Matemática para el Dinero Esperado en Gaveta (Efectivo Físico):
        $expectedInBox = bcadd((string)$this->opening_balance, $totalInitialFund, 4); // + Base/Apertura + Fondo Inicial
        $expectedInBox = bcadd($expectedInBox, $totalContado, 4);                     // + Ventas en efectivo (Contado)
        $expectedInBox = bcadd($expectedInBox, $totalIncome, 4);                      // + Entradas de efectivo manuales

        $expectedInBox = bcsub($expectedInBox, $totalExpense, 4);                    // - Gastos / Salidas de efectivo
        $expectedInBox = bcsub($expectedInBox, $totalVaultDeposit, 4);
        // Resta únicamente los gastos/salidas realizados

        return [
            'uuid' => $this->uuid,
            'opening_balance' => (string)$this->opening_balance,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),

            // 📊 Totales calculados para auditoría e interfaz
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'total_vault_deposit' => $totalVaultDeposit,
            'total_initial_fund' => $totalInitialFund,


            // Datos de la ventas
            'total_contado' => $totalContado,
            'total_credito' => $totalCredito,
            'total_transferencia' => $totalTransferencia,
            'total_cheque' => $totalCheque,
            'total_anticipo' => $totalAnticipo,
            'total_tarjeta' => $totalTarjeta,

            // 🎯 El monto exacto que el cajero DEBE tener físicamente al contar
            'expected_balance' => $expectedInBox,

            // Si necesitas renderizar la lista completa de transacciones en la vista:
            'movements' => $this->movements->map(fn($m) => [
                'uuid' => $m->uuid,
                'type' => $m->type->value,
                'type_label' => $m->type->label(),
                'amount' => (string)$m->amount,
                'concept' => $m->concept,
                'comment' => $m->comment,
                'created_at' => $m->created_at?->toIso8601String(),
            ]),
        ];
    }
}

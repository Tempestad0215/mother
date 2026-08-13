<?php

namespace App\Enums;

enum PaMovementType: string
{
    // ==========================================
    // ENTRADAS (PA - Posting de Entrada)
    // ==========================================

    /** Entrada por Compra - Recepción de mercancía de proveedores */
    case PURCHASE_ENTRY = 'purchase_entry';

    /** Entrada por Devolución de Clientes - Clientes devuelven productos */
    case RETURN_FROM_CUSTOMER = 'return_from_customer';

    /** Entrada por Transferencia - Recepción desde otro almacén */
    case TRANSFER_ENTRY = 'transfer_entry';

    /** Entrada por Ajuste de Inventario - Corrección de stock positivo */
    case ADJUSTMENT_ENTRY = 'adjustment_entry';

    /** Entrada Inicial - Carga inicial de inventario */
    case INITIAL_ENTRY = 'initial_entry';

    /** Entrada por Producción - Productos fabricados internamente */
    case PRODUCTION_ENTRY = 'production_entry';

    /** Entrada por Donación - Recepción de donaciones */
    case DONATION_ENTRY = 'donation_entry';

    /** Entrada por Importación - Mercancía importada */
    case IMPORT_ENTRY = 'import_entry';

    // ==========================================
    // SALIDAS (SA - Salida Manual)
    // ==========================================

    /** Salida por Venta - Venta a clientes */
    case SALE_EXIT = 'sale_exit';

    /** Salida por Devolución a Proveedores - Devolver productos a proveedores */
    case RETURN_TO_SUPPLIER = 'return_to_supplier';

    /** Salida por Transferencia - Envío a otro almacén */
    case TRANSFER_EXIT = 'transfer_exit';

    /** Salida por Ajuste de Inventario - Corrección de stock negativo */
    case ADJUSTMENT_EXIT = 'adjustment_exit';

    /** Salida por Merma - Productos dañados o vencidos */
    case LOSS_EXIT = 'loss_exit';

    /** Salida por Consumo Interno - Uso interno de la empresa */
    case INTERNAL_CONSUMPTION = 'internal_consumption';

    /** Salida por Muestra - Envío de muestras */
    case SAMPLE_EXIT = 'sample_exit';

    /** Salida por Préstamo - Productos prestados */
    case LOAN_EXIT = 'loan_exit';

    // ==========================================
    // MÉTODOS DE UTILIDAD
    // ==========================================

    /**
     * Obtener el prefijo del documento
     */
    public function getPrefix(): string
    {
        return match($this) {
            self::PURCHASE_ENTRY,
            self::RETURN_FROM_CUSTOMER,
            self::TRANSFER_ENTRY,
            self::ADJUSTMENT_ENTRY,
            self::INITIAL_ENTRY,
            self::PRODUCTION_ENTRY,
            self::DONATION_ENTRY,
            self::IMPORT_ENTRY => 'PA',

            self::SALE_EXIT,
            self::RETURN_TO_SUPPLIER,
            self::TRANSFER_EXIT,
            self::ADJUSTMENT_EXIT,
            self::LOSS_EXIT,
            self::INTERNAL_CONSUMPTION,
            self::SAMPLE_EXIT,
            self::LOAN_EXIT => 'SA',
        };
    }

    /**
     * Determina si es una entrada (PA)
     */
    public function isEntry(): bool
    {
        return in_array($this, [
            self::PURCHASE_ENTRY,
            self::RETURN_FROM_CUSTOMER,
            self::TRANSFER_ENTRY,
            self::ADJUSTMENT_ENTRY,
            self::INITIAL_ENTRY,
            self::PRODUCTION_ENTRY,
            self::DONATION_ENTRY,
            self::IMPORT_ENTRY,
        ]);
    }

    /**
     * Determina si es una salida (SA)
     */
    public function isExit(): bool
    {
        return in_array($this, [
            self::SALE_EXIT,
            self::RETURN_TO_SUPPLIER,
            self::TRANSFER_EXIT,
            self::ADJUSTMENT_EXIT,
            self::LOSS_EXIT,
            self::INTERNAL_CONSUMPTION,
            self::SAMPLE_EXIT,
            self::LOAN_EXIT,
        ]);
    }

    /**
     * Obtener el label (nombre amigable)
     */
    public function getLabel(): string
    {
        return match($this) {
            // Entradas
            self::PURCHASE_ENTRY => 'Entrada por Compra',
            self::RETURN_FROM_CUSTOMER => 'Devolución de Cliente',
            self::TRANSFER_ENTRY => 'Entrada por Transferencia',
            self::ADJUSTMENT_ENTRY => 'Ajuste de Inventario (+)',
            self::INITIAL_ENTRY => 'Carga Inicial de Inventario',
            self::PRODUCTION_ENTRY => 'Entrada por Producción',
            self::DONATION_ENTRY => 'Recepción de Donación',
            self::IMPORT_ENTRY => 'Entrada por Importación',

            // Salidas
            self::SALE_EXIT => 'Salida por Venta',
            self::RETURN_TO_SUPPLIER => 'Devolución a Proveedor',
            self::TRANSFER_EXIT => 'Salida por Transferencia',
            self::ADJUSTMENT_EXIT => 'Ajuste de Inventario (-)',
            self::LOSS_EXIT => 'Merma / Pérdida',
            self::INTERNAL_CONSUMPTION => 'Consumo Interno',
            self::SAMPLE_EXIT => 'Envío de Muestra',
            self::LOAN_EXIT => 'Préstamo de Productos',
        };
    }

    /**
     * Obtener el color/severidad para UI
     */
    public function getSeverity(): string
    {
        return match($this) {
            self::PURCHASE_ENTRY,
            self::INITIAL_ENTRY,
            self::PRODUCTION_ENTRY,
            self::IMPORT_ENTRY => 'success',

            self::RETURN_FROM_CUSTOMER,
            self::TRANSFER_ENTRY,
            self::DONATION_ENTRY => 'info',

            self::ADJUSTMENT_ENTRY,
            self::ADJUSTMENT_EXIT => 'warning',

            self::SALE_EXIT,
            self::LOSS_EXIT,
            self::SAMPLE_EXIT => 'danger',

            self::RETURN_TO_SUPPLIER,
            self::TRANSFER_EXIT,
            self::INTERNAL_CONSUMPTION,
            self::LOAN_EXIT => 'secondary',
        };
    }


    /**
     * Obtener todos los tipos de entrada (PA)
     */
    public static function getEntryTypes(): array
    {
        return array_filter(self::cases(), fn($type) => $type->isEntry());
    }

    /**
     * Obtener todos los tipos de salida (SA)
     */
    public static function getExitTypes(): array
    {
        return array_filter(self::cases(), fn($type) => $type->isExit());
    }

}

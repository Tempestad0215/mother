<?php

namespace App\Enums;

enum InventoryMovementConceptEnum: string
{
    // Salidas (OUT)
    case Venta = 'sale';
    case Vencido = 'expired';
    case Danado = 'damaged';
    case Robo = 'stolen';
    case AjusteNegativo = 'adjustment_negative';
    case TransferenciaSalida = 'transfer_out';

    // Entradas (IN)
    case Compra = 'purchase';
    case Recepcion = 'reception'; // Recepción de mercancía de un proveedor
    case Devolucion = 'return';   // Devolución de un cliente
    case AjustePositivo = 'adjustment_positive';
    case TransferenciaEntrada = 'transfer_in';

    /**
     * Retorna el texto amigable para mostrar en la interfaz (Vue/Inertia)
     */
    public function label(): string
    {
        return match($this) {
            self::Venta => 'Venta en POS',
            self::Vencido => 'Producto Vencido',
            self::Danado => 'Producto Roto / Dañado',
            self::Robo => 'Pérdida o Robo',
            self::AjusteNegativo => 'Ajuste de Inventario (-)',
            self::TransferenciaSalida => 'Transferencia (Salida)',

            self::Compra => 'Compra a Proveedor',
            self::Recepcion => 'Recepción de Mercancía',
            self::Devolucion => 'Devolución de Cliente',
            self::AjustePositivo => 'Ajuste de Inventario (+)',
            self::TransferenciaEntrada => 'Transferencia (Entrada)',
        };
    }
}

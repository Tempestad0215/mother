<?php

namespace App\Enums;

enum InventoryMovementTypeEnum:string
{
    case Entrada = 'Entrada';
    case Salida = 'Salida';
    case Ajuste = 'Ajuste';
    case Transferencia = 'Transferencia';
    case Venta = 'Venta';
    case Cotizacion = 'Cotizacion';
    case Devolucion = 'Devolucion';
    case Recepcion = 'Recepcion';
}

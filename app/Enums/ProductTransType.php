<?php

namespace App\Enums;

Enum ProductTransType:string {
    case  ENTRADA = 'entrada';

    case RESERVA = 'reserva';
    case VENTAS = 'ventas';
    case  SALIDA = 'salida';
    case  INTERNO = 'interno';
    case  AJUSTE = 'ajuste';
    case  OTROS = 'otros';
    case ELIMINADO = 'eliminado';
    case DEVOLUCION = 'devolucion';
}

<?php

namespace App\Enums;

Enum TransTypeEnum:string {
    case  ENTRADA = 'ENTRADA';

    case RESERVA = 'RESERVA';
    case VENTAS = 'VENTAS';
    case  SALIDA = 'SALIDA';
    case  INTERNO = 'INTERNO';
    case  AJUSTE = 'AJUSTE';
    case  OTROS = 'OTROS';
    case ELIMINADO = 'ELIMINADO';
    case DEVOLUCION = 'DEVOLUCION';
}

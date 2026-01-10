<?php

namespace App\Enums;

enum InventoryMovementTypeEnum:string
{
    case ENTRADA = 'ENTRADA';
    case SALIDA = 'SALIDA';
    case AJUSTE = 'AJUSTE';
    case CONTEO = 'CONTEO';
}

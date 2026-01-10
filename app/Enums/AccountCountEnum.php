<?php

namespace App\Enums;

enum AccountCountEnum:string
{
    case ACTIVO = 'ACTIVO';
    case PASIVO = 'PASIVO';
    case INGRESO = 'INGRESO';
    case GASTO = 'GASTO';
    case CAPITAl = 'CAPITAL';
}

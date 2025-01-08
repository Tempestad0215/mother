<?php

namespace App\Enums;

enum STACEnum: string
{
    case PENDIENTE = "pending";
    case  PARCIAL = "partial";
    case PAGADO = "paid";
    case VENCIDA = "overdue";
}

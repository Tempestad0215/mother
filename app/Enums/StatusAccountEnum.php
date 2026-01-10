<?php

namespace App\Enums;

enum StatusAccountEnum: string
{
    case PENDIENTE = "pending";
    case  PARCIAL = "partial";
    case PAGADO = "paid";
    case VENCIDA = "overdue";
}

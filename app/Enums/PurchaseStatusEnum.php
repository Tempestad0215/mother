<?php

namespace App\Enums;

enum PurchaseStatusEnum: string
{
    case Borrador = 'Borrador';
    case Pendiente = 'Pendiente';
    case Parcial = 'Parcial';
    case Completada = 'Completada';
    case Cancelada = 'Cancelada';
}

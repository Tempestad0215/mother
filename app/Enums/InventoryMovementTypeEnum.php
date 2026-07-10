<?php

namespace App\Enums;

enum InventoryMovementTypeEnum: string
{
    case IN = 'IN';   // Suma al inventario
    case OUT = 'OUT'; // Resta al inventario
}

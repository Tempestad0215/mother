<?php

namespace App\Helpers;

use App\Models\InventoryMovement;

class InventoryMovementHelper
{

    public static function multipleInsert(array $data)
    {
        if(empty($data))
        {
            return;
        }

        InventoryMovement::insert($data);
    }

}

<?php

namespace App\Helpers;

use App\Models\InventoryMovement;

class InventoryMovementHelper
{

    /**
     * @param array $data
     * @return void
     */
    public static function multipleInsert(array $data): void
    {
        if(empty($data))
        {
            return;
        }

        InventoryMovement::insert($data);
    }

}

<?php

namespace App\Helpers;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;

class SupplierHelper
{

    /**
     * @return Collection
     */
    public function getAllSuppliers()
    {
        return Supplier::all();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    //


    //  Almacenar los datos
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'cost',
        'description',
        'date',
        'status'
    ];



}

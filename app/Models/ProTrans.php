<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProTrans extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stock',
        'price',
        'type'
    ];
}

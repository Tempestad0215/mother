<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'unit',
        'stock',
        'cost',
        'supplier_id',
        'status'
    ];


    protected $hidden = [
        'status',
        'created_at',
        'update_at'
    ];



    // Relaciones

    public function supplier():BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

}

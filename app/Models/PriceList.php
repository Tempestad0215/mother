<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceList extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'status' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'name',
        'description',
    ];


    public function name():Attribute
    {
        return Attribute::make(
            get:fn(string $value) => strtoupper($value),
            set:fn(string $value) => strtoupper($value),
        );
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}

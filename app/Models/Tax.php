<?php

namespace App\Models;

use App\Enums\ModelStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'rate',
        'model_status',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function name():Attribute
    {
        return Attribute::make(
            get: fn(string $value) => strtoupper($value),
            set: fn(string $value) => strtoupper($value),
        );
    }

    protected function casts(): array
    {
        return [
            'model_status' => ModelStatusEnum::class,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property null|string $description
 * @property boolean $status
*/
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];


    protected $casts = [
        'status'=> 'boolean',
    ];


    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'addres',
        'status',
    ];


    protected $casts = [
        'status'=> 'boolean',
    ];
}

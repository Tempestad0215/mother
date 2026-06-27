<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashMovement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cash_register',
        'type',
        'amount',
        'concept',
        'comment',
    ];
}

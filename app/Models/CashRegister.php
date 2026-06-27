<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashRegister extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_uuid',
        'opening_balance',
        'closing_balance',
        'expected_balance',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'opened_at' => 'timestamp',
            'closed_at' => 'timestamp',
        ];
    }
}

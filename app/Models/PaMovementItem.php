<?php

namespace App\Models;

use App\Traits\HasCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class PaMovementItem extends Model
{
    use HasUuids, SoftDeletes;
    use HasCode;
    use LogsActivity;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'product_uuid',
        'warehouse_uuid',
        'cost',
        'quantity',
        'tax',
        'tax_uuid',
        'amount',
    ];
}

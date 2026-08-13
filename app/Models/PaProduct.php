<?php

namespace App\Models;

use App\Traits\HasCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class PaProduct extends Model
{
    use SoftDeletes;
    use HasCode;
    use LogsActivity;

    protected $fillable = [
        'code',
        'supplier_uuid',
        'document_date',
        'comment',
        'total',
        'tax',
        'sub_total',
    ];
}

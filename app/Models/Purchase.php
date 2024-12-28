<?php

namespace App\Models;

use App\Enums\PurchaseProcessEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string uuid
 * @property string supplier_id
 * @property object info
 * @property float amount
 * @property float tax
 * @property float sub_total
 * @property PurchaseProcessEnum process
 * @property bool status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 */
class Purchase extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    //Para guardar los datos
    protected $fillable = [
        'supplier_id',
        'info',
        'amount',
        'tax',
        'sub_total',
        'proccess',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'info' => 'json',
        ];
    }
}

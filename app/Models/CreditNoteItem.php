<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CreditNoteItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;

    protected $table = 'credit_note_items';

    // Datos para actualizar masivamente
    protected  $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;
    

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'credit_note_uuid',
        'product_id',
        'quantity',
        'price',
        'sub_total',
        'tax',
        'amount'
    ];


    /**
     * Summary of items
     * @return HasMany<CreditNoteItem, CreditNoteItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class, 'credit_note_uuid', 'uuid');
    }

}
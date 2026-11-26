<?php

namespace App\Models;

use App\Observers\CreditNoteItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $credit_note_uuid
 * @property string $product_uuid
 * @property numeric $quantity
 * @property numeric $price
 * @property numeric $tax
 * @property numeric $sub_total
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $warehouse_uuid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\CreditNote|null $creditNote
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereCreditNoteUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem whereWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteItem withoutTrashed()
 * @mixin \Eloquent
 */

#[ObservedBy([CreditNoteItemObserver::class])]
class CreditNoteItem extends Model
{
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;

    protected $table = 'credit_note_items';

    // Datos para actualizar masivamente
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'credit_note_uuid',
        'quantity',
        'price',
        'sub_total',
        'tax',
        'amount',
        'product_uuid',
        'warehouse_uuid',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }


    /**
     * @return BelongsTo
     */
    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_uuid', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_uuid', 'uuid');
    }

}

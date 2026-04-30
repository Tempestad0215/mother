<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $location
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * @property-read PurchaseReceiptsItem $receiptsItem
 */
class Warehouse extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'location'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function purchaseItem(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function receiptItem(): HasMany
    {
        return $this->hasMany(PurchaseReceiptsItem::class);
    }
}

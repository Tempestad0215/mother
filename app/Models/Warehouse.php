<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int id
 * @property string name
 * @property string description
 * @property string location
 * @property Carbon deleted_at
 * @property Carbon updated_at
 * @property Carbon created_at
 */
class Warehouse extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'location'
    ];


    public function purchaseItem(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}

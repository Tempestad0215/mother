<?php

namespace App\Models;

use App\Enums\PurchaseStatusEnum;
use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $code codigo unido para cada registro
 * @property string $supplier_uuid Relacion con el suplidor de la orden
 * @property string $user_uuid Relacion para el usuario
 * @property string $doc_date
 * @property numeric $amount valor total de la compra
 * @property numeric $tax impuesto de la compra
 * @property numeric $sub_total Sub total de la compra
 * @property numeric $discount Descuento Global
 * @property string $status Estado de la orden de compra
 * @property string|null $comment Comentario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryMovement> $itemMovements
 * @property-read int|null $item_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Database\Factories\PurchaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDocDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereSupplierUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUserUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase withoutTrashed()
 * @mixin \Eloquent
 */
class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuids;
    use LogsActivity;

    protected $primaryKey = "uuid";
    protected $keyType = 'string';
    public $incrementing = false;


    //Para guardar los datos
    protected $fillable = [
        'supplier_uuid',
        'doc_date',
        'code',
        'user_uuid',
        'amount',
        'tax',
        'sub_total',
        'status',
        'type',
        'quantity',
        'cost',
        'discount',
        'description'
    ];

    protected function casts(): array
    {
        return [
            'info' => 'json',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo los codigos
        static::creating(function ($model) {
            $model->code = self::generateCode($model);
        });
    }


    /**
     * @param Model $model
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode(Model $model):string
    {
        //        Contar los nuemros totales
        $nextNumber = self::withTrashed()->count('uuid') + 1;

        // Generar el proximo ID
        return CodeHelper::generateCode($model, $nextNumber);
    }

    public function itemMovements():MorphMany
    {
        return $this->morphMany(InventoryMovement::class, 'movementable');
    }

    public function items():HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

}

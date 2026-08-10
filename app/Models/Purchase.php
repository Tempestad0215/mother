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


/**
 * @property string $id
 * @property string $supplier_uuid
 * @property object $info
 * @property float $amount
 * @property float $tax
 * @property float $sub_total
 * @property PurchaseStatusEnum $process
 * @property bool $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 *
 * @property-read Supplier $supplier
 * @property-read PurchaseItem[] $items
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

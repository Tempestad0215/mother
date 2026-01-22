<?php

namespace App\Models;

use App\Enums\PurchaseStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int id
 * @property string supplier_id
 * @property object info
 * @property float amount
 * @property float tax
 * @property float sub_total
 * @property PurchaseStatusEnum process
 * @property bool status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 */
class Purchase extends Model
{
    use HasFactory, SoftDeletes;


    //Para guardar los datos
    protected $fillable = [
        'supplier_id',
        'doc_date',
        'code',
        'user_id',
        'amount',
        'tax',
        'sub_total',
        'status',
        'type',
        'warehouse_id',
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
            $model->code = self::generateCode();
        });
    }


    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode():string
    {
        // Obtener el ultimo registros
        $total = self::count();

        // Generar el proximo ID
        $nextID = $total ? $total + 1 : 1;

        // Devolver los datos
        $code = config('appconfig.purchase');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
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

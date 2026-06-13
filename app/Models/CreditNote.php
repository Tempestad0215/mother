<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property integer $uuid
 * @property string $code
 * @property string $ncf
 * @property string $ncf_m
 * @property string $client_rnc
 * @property string $client_name
 * @property int $client_uuid
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property float $n_available
 * @property float $n_used
 * @property boolean $status
 * @property Carbon $created_at,
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property ProductTransaction[] $trans
 * @property Sale $sale_id,
 * @property PaymentTypeEnum $type_payment,
 * @property-read CreditNoteItem $items
 * @property float $received
 * @property float $returned
 * @property string $comment
 */
class CreditNote extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;


    // La tabla que se ve a utilizar
    protected $table = 'credit_notes';


    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    // Datos para actualizar masivamente
    protected $fillable = [
        'code',
        'ncf',
        'ncf_m',
        'invoice_type',
        'client_name',
        'client_rnc',
        'client_id',
        'sale_uuid',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'n_available',
        'n_used',
        'type',
        'status',
        'comment'
    ];


    /**
     * Summary of credit
     * @return BelongsTo<CreditNote, CreditNote>
     */
    public function credit(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_uuid', 'uuid');
    }


    /**
     * Summary of comment
     * @return MorphOne<Comment, CreditNote>
     */
    public function comment(): MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    /**
     * Summary of trans
     * @return HasMany<ProductTransaction, CreditNote>
     */
    public function trans(): HasMany
    {
        return $this->hasMany(ProductTransaction::class, 'credit_note_uuid');

    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class, 'credit_note_uuid', 'uuid');
    }

    /**
     * Summary of sale
     * @return BelongsTo<Sale, CreditNote>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }


    /**
     * Summary of boot
     */
    protected static function boot(): void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo los codigos
        static::creating(function ($model) {
            $model->code = self::generateCode($model);
        });
    }



    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode(Model $model): string
    {
        // Obtener el ultimo registros
        $nextNumber = self::withTrashed()->count('uuid') + 1;

        return CodeHelper::generateCode($model, $nextNumber);
    }


}

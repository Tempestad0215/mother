<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string $code codigo
 * @property string|null $ncf ncf
 * @property string|null $invoice_type tipo de factura
 * @property string|null $client_name nombre del cliente
 * @property string|null $client_rnc rnc del cliente
 * @property string|null $client_id relacion de cliente
 * @property numeric $discount_amount Cantidad en descuento
 * @property numeric $tax Impuesto
 * @property numeric $sub_total Subtotal
 * @property numeric $amount Cantidad Total
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $sale_uuid Relacion con la venta
 * @property string|null $ncf_m ncf modificado
 * @property numeric $n_available cantiad nota de credito disponible
 * @property string $type Tipo Devolucion
 * @property bool $status Estado
 * @property \App\Models\Comment|null $comment Comentario
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read CreditNote|null $credit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CreditNoteItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Sale|null $sale
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $saleCreditNote
 * @property-read int|null $sale_credit_note_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductTransaction> $trans
 * @property-read int|null $trans_count
 * @method static \Database\Factories\CreditNoteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereClientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereClientRnc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereInvoiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereNAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereNcf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereNcfM($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereSaleUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote withoutTrashed()
 * @mixin \Eloquent
 */
class CreditNote extends Model{

    use SoftDeletes;
    use HasUuids;
    use LogsActivity;
    use HasFactory;


    // La tabla que se ve a utilizar
    protected $table = 'credit_notes';

    // Los datos
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
        'client_uuid',
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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * @return BelongsToMany
     */
    public function saleCreditNote():BelongsToMany
    {
        return $this->belongsToMany(Sale::class);
    }

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
        return $this->belongsTo(Sale::class,'sale_uuid','uuid');
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

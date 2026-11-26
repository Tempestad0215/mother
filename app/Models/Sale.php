<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Traits\HasCode;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Ramsey\Collection\Collection;
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
 * @property string $created_at
 * @property string $updated_at
 * @property SaleTypeEnum $type Tipo de Ventas
 * @property PaymentTypeEnum $type_payment Tipo de Pago
 * @property numeric $received Valor Recibido
 * @property numeric $returned Valor Devuelto
 * @property bool|null $status Estado
 * @property bool $close_table Cuentas abierto o cerrada
 * @property string|null $credit_notes Pago por nota de credito
 * @property float $credit_notes_amount Monto de todas las notas de creditos
 * @property string|null $comment Comentario
 * @property string|null $cash_register_uuid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\CashRegister|null $cashRegister
 * @property-read \App\Models\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CreditNote> $creditNotes
 * @property-read int|null $credit_notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductTransaction> $credit_trans
 * @property-read int|null $credit_trans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $items
 * @property-read int|null $items_count
 * @method static \Database\Factories\SaleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale searchByCode($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCashRegisterUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereClientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereClientRnc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCloseTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreditNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreditNotesAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereInvoiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereNcf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereReturned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTypePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale withoutTrashed()
 * @mixin \Eloquent
 */
#[ObservedBy([SaleObserver::class])]
class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;
    use HasCode;

    // La tabla que se ve a utilizar
    protected $table = 'sales';

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    protected string $codePrefix = 'FACT';

    /**
     * Guardar los datos
     * @var array
     */
    protected $fillable = [
        'ncf',
        'invoice_type',
        'code',
        'client_name',
        'client_id',
        'client_rnc',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'type',
        'type_payment',
        'received',
        'returned',
        'status',
        'close_table',
        'comment',
        'cash_register_uuid'
    ];

    //Formatear los datos
    protected $casts = [
        'status' => 'boolean',
        'amount' => 'decimal:4',
        'sub_total' => 'decimal:4',
        'discount_amount' => 'decimal:4',
        'received' => 'decimal:4',
        'returned' => 'decimal:4',
        'tax' => 'decimal:4',
        'close_table' => 'boolean',
        'type' => SaleTypeEnum::class,
        'type_payment' => PaymentTypeEnum::class,
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }


    /*
     * Relaciones
     */

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_uuid', 'uuid');
    }


    /**
     * @return HasMany
     *
     */
    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class, 'sale_uuid', 'uuid');
    }


    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_uuid', 'uuid');
    }

    /**
     * @return HasManyThrough
     */
    public function credit_trans(): HasManyThrough
    {
        return $this->hasManyThrough(ProductTransaction::class, CreditNote::class, 'sale_uuid', 'credit_note_uuid', 'uuid');
    }


    /**
     * Formatear la fehca de creacion
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('d/m/Y H:i:s'),
            set: fn(string $value) => Carbon::parse($value)->format('Y/m/d H:i:s'),
        );
    }

    /**
     * Formataer la fecha de actualizacion
     * @return Attribute
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('d/m/Y H:i:s'),
            set: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }


}

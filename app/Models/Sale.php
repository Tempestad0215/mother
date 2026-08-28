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
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

/**
 * @property string $uuid
 * @property string $code
 * @property string $invoice_type
 * @property string $ncf
 * @property string $ncf_m
 * @property string $client_rnc
 * @property string $client_name
 * @property string $client_uuid
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property boolean $status
 * @property SaleTypeEnum $type
 * @property bool $close_table
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property ProductTransaction[] $infoSale
 * @property PaymentTypeEnum $type_payment
 * @property float $received
 * @property float $returned
 * @property string[] $credit_notes
 * @property float $credit_notes_amount
 * @property string $comment
 * @property string $cash_register_uuid
 *
 *
 * @property-read Collection<int, SaleItem> $items
 * @property-read Collection<int, CreditNote> $creditNotes
 * @property-read Collection<int, CreditNoteSale> $creditNoteSales
 */
#[ObservedBy([SaleObserver::class])]
class Sale extends Model
{
    use HasFactory;
    use softDeletes;
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

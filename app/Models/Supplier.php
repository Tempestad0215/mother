<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $code codigo unido para cada registro
 * @property string|null $contact Nombre del representante
 * @property string $company_name nombre completo
 * @property PaymentTypeEnum $type_payment
 * @property string|null $phone Telefono
 * @property string|null $email Correo electronico
 * @property bool $receive_email Email Recibido
 * @property string|null $account_bank Cuenta de Banco
 * @property bool $is_recurring Pago Recurrente
 * @property int|null $payment_day Dia de pago del mes
 * @property bool $status Estado
 * @property \App\Models\Comment|null $comment Comentario
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $product
 * @property-read int|null $product_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Purchase> $purchase
 * @property-read int|null $purchase_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseReceipts> $receipts
 * @property-read int|null $receipts_count
 * @method static \Database\Factories\SupplierFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAccountBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePaymentDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereReceiveEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTypePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withoutTrashed()
 * @mixin \Eloquent
 */

class Supplier extends Model
{
    use Searchable;
    use HasFactory;
    use softDeletes;
    use HasUuids;
    use LogsActivity;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    //Registro masivo
    protected $fillable = [
        'contact',
        'company_name',
        'phone',
        'type_payment',
        'email',
        'receive_email',
        'account_bank',
        'is_recurring',
        'status',
        'payment_day',
        'comment'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }


    // formatear los datos
    protected $casts = [
        'status' => 'boolean',
        'type_payment' => PaymentTypeEnum::class,
    ];


    public function purchase(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    // Rleaciones
    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }


    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }


    public function account():MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(PurchaseReceipts::class);
    }

    /*
     * Para buscar los datos
     */
    public function toSearchableArray():array
    {
        return [
            'company_name' => $this->company_name,
            'contact' => $this->contact,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }


    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el método principal
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
    private static function generateCode(Model $model):string
    {
        // Obtener el ultimo registros
        $total = self::withTrashed()->count() + 1;

        return  CodeHelper::generateCode($model, $total);
    }



}

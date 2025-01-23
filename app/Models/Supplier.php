<?php

namespace App\Models;

use App\Enums\SalePaymentTypeEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int id
 * @property string|null contact
 * @property string company_name
 * @property string|null phone
 * @property SalePaymentTypeEnum type_payment
 * @property string|null email
 * @property bool receive_email
 * @property string account_bank
 * @property bool is_recurring
 * @property integer payment_day
 * @property boolean status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
*/

class Supplier extends Model implements Auditable
{
    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


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
        'payment_day'
    ];


    // formatear los datos
    protected $casts = [
        'status' => 'boolean',
        'type_payment' => SalePaymentTypeEnum::class,
    ];


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
        $code = config('appconfig.supplier');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }



}

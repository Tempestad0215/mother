<?php

namespace App\Models;

use App\Enums\CLDOCENUM;
use App\Enums\CLTYEnum;
use App\Enums\CLTYPRIEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property integer id;
 * @property string $name
 * @property string $phone
 * @property string $personal_id
 * @property string $email
 * @property CLDOCENUM $document
 * @property string $address
 * @property boolean $status
 * @property float $limit
 * @property integer $due_date
 * @property CLTYEnum $type
 * @property float $late_fee_interest
 * @property float $balance
 * @property float $consumed
 * @property CLTYPRIEnum $type_price
 * @property boolean $receive_email
 * @property Date $deleted_at
 * @property Date $created_at
 * @property Date $updated_at
 */

class Client extends Model implements Auditable
{


    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'document',
        'personal_id',
        'phone',
        'email',
        'address',
        'status',
        'type'
    ];


    protected $casts = [
        'type' => CLTYEnum::class,
        'document' => CLDOCENUM::class,
        'type_price' => CLTYPRIEnum::class,
        'status'=> 'boolean',
    ];


    /**
     * Para buscar los datos
     * @return array
     */
    #[SearchUsingPrefix([ 'email'])]
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'document' => $this->document,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }

    /**
     * Relaciones
     */
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }


    public function image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    public function account():MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    /**
     * Cliente
     * @return HasOne
     */
    public function sale():HasOne
    {
        return $this->hasOne(Sale::class);
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
        $code = config('appconfig.cliCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}

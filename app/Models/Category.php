<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string uuid
 * @property string code
 * @property string name
 * @property null|string description
 * @property boolean status
 * @property Carbon deleted_at
 * @property Carbon updated_at
 * @property Carbon created_at
*/
class Category extends Model implements Auditable
{
    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'code',
        'name',
        'description',
        'status',
        'deleted_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status'=> 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }


    /**
     * Para la busqueda de los datos
     * @return array
     */
    public function toSearchableArray():array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
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
        $code = config('appconfig.category');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }

}

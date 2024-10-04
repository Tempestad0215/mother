<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $id
 * @property string|null $contact
 * @property string $company_name
 * @property string|null $phone
 * @property string|null $email
 * @property boolean $status
 * @property Date $deleted_at
*/

class Supplier extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


    protected $fillable = [
        'contact',
        'company_name',
        'phone',
        'email',
        'status'
    ];


    protected $casts = [
        'status' => 'boolean'
    ];


    // Rleaciones
    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }



    /**
     * @return void
     */
    protected static function boot()
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo en el codigo
        static::creating(function ($supplier) {
            $supplier->code = self::generateCode();
        });
    }



    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode()
    {
        // Obtener el ultimo registros
        $last = self::orderBy('id','desc')->first();

        // Generar el proximo ID
        $nextID = $last ? $last->id + 1 : 1;

        // Devolver los datos
        $code = config('appconfig.supCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;


/**
 * @property string|null $contact
 * @property string $company_name
 * @property string|null $phone
 * @property string|null $email
 * @property boolean $status
*/

class Supplier extends Model
{
    use HasFactory;


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
    private static function generateCode()
    {

        //codigo de producto
        $code = config('Setting.supCode');

        // Sacar el ultimo producto
        $lastProduct = DB::table('suppliers')->latest('id')->first();

        //Verificar si existe
        if($lastProduct){
            //Extraer el numero secuencial
            $lastNumber = (int)substr($lastProduct->code, 3);
            $newNumber = str_pad(++$lastNumber, 8, "0", STR_PAD_LEFT);
        }else{
            $newNumber = '000001';
        }

        // Generar el nuevo codigo
        return $code . $newNumber;
    }

}

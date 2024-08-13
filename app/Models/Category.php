<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property string $name
 * @property null|string $description
 * @property boolean $status
*/
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];


    protected $casts = [
        'status'=> 'boolean',
    ];


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

        //Generar el codigo en todo
        static::creating(function ($category) {
            $category->code = self::generateCode();
        });
    }



    /**
     * @return string
     */
    private static function generateCode()
    {

        //codigo de producto
        $code = config('Setting.catCode');

        // Sacar el ultimo producto
        $lastProduct = DB::table('categories')->latest('id')->first();

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'addres',
        'status',
    ];


    protected $casts = [
        'status'=> 'boolean',
    ];


    /**
     * @return void
     */
    protected static function boot()
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo en todo
        static::creating(function ($client) {
            $client->code = self::generateCode();
        });
    }



    /**
     * @return string
     */
    private static function generateCode()
    {

        //codigo de producto
        $code = config('Setting.cliCode');

        // Sacar el ultimo producto
        $lastProduct = DB::table('clients')->latest('id')->first();

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

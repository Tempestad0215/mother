<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string client_name
 * @property int client_id
 * @property array info
 * @property float discount
 * @property float itbis
 * @property float sub_total
 * @property float total
 * @property boolean status
 */


class Sale extends Model
{
    use HasFactory;


    // La tabla que se ve a utilizar
    protected $table = 'sales';


    // Datos para actualizar masivamente
    protected $fillable = [
        'client_name',
        'client_id',
        'info',
        'discount',
        'itbis',
        'sub_total',
        'total',
        'status'
    ];


    //Formatear los datos
    protected  $casts = [
        'status' => 'boolean',
        'info' => 'json'
    ];

}

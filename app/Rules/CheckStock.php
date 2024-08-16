<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckStock implements ValidationRule
{
    protected array $info;

    public  function __construct($info)
    {
        $this->info = $info;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $existsError = false;
        $errorMessage = '';

        //Sacar los datos del producto introducido para validar
        foreach ($this->info as $info) {
            //Buscar los datos del producto
            $product = Product::where('status', false)
                ->findOrFail($info['id']);
            //Tomar los datos de la cantidad
            $quantity = $info['quantity'];

            //Verificar si existe en reservado
            if($product->reserved > 0)
            {
                //Restar la cantidad de la reserva
                $quantity -= $product->reserved;

                //Realizar la verificacion
                if($quantity > $product->stock){
                    // Enviar el mensaje de que no puede ser mayor
                    $existsError = true;
                    $errorMessage = 'El Producto "' . $info['name'] . '" no tiene suficiente stock.';
                    break;
                }
            }


        }

        //Verificar si existe un mensaje de error
        if($existsError)
        {
            $fail($errorMessage);
        }

    }
}

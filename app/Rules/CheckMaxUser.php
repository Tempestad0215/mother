<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckMaxUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //Conseguir la cantidad maxima de usuario
        $maxUser = config('setting.maxUser');

        //contar todos los usuarios habilitados
        $user = User::where('status',0)->count();


        //Verificar que la cantidad de usuarios no sea mayor al maximo
        if($user > $maxUser)
        {
            //Mensje en caso de que falle la validacion
            $fail('Usted ha alcanzado el maximo de usuarios, comuniquese con el administrador');
        }

        //
    }
}

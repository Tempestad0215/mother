<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            // crear la validacion de todo
            $validated = $request->validate([
                'name' => ['required','string','min:4','max:75'],
                'email' => ['required','string','email','max:150'],
                'password'=> ['required','string',Password::min(8),'confirmed'],
                'role' => ['required',Rule::enum(UserRoleEnum::class),'numeric'],
            ]);

            // Guardar los datos ya validados
            User::create($validated);


            // Retornar hacia atras
            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }


}

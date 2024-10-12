<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Rules\CheckMaxUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // crear la validacion
        $validated = $request->validate([
            'name' => ['required','string','min:4','max:75'],
            'email' => ['required','string','email','max:150',new CheckMaxUser()],
            'password'=> ['required','string',Password::min(8),'confirmed'],
            'role' => ['required',Rule::enum(UserRoleEnum::class),'numeric'],
        ]);

        // Guardar los datos ya validados
        User::create($validated);

        // Retornar hacia atras
        return back();
    }

    public function update(Request $request, User $user)
    {
        if($request->modify_password)
        {
            // crear la validacion
            $validated = $request->validate([
                'name' => ['required','string','min:4','max:75'],
                'email' => ['required','string','email','max:150',new CheckMaxUser(), Rule::unique('users', 'email')->ignore($user)],
                'password'=> ['required','string',Password::min(8),'confirmed'],
                'role' => ['required',Rule::enum(UserRoleEnum::class),'numeric'],
            ]);
        }else{
            // crear la validacion
            $validated = $request->validate([
                'name' => ['required','string','min:4','max:75'],
                'email' => ['required','string','email','max:150',new CheckMaxUser(), Rule::unique('users', 'email')->ignore($user)],
                'role' => ['required',Rule::enum(UserRoleEnum::class),'numeric'],
            ]);
        }


        ;

        //Actualziar los datos
        $user->update($validated);

        //Retornar hacia atras
        return back();
    }


    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        //Eliminar los datos
        $user->status = true;
        $user->save();

        //Devolver hacia atras
        return back();
    }


}

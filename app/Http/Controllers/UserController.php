<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Rules\CheckMaxUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller implements HasMiddleware
{

    /**
     * Para los middleware del controllador
     * @return array
     */
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('role:Super Admin|Supervisor',),
        ];
    }

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
            'role' => ['required','string','exists:roles,name'],
        ]);

        DB::transaction(function () use ($validated) {
            // Guardar los datos ya validados
            $user = User::create($validated);


            //Colocar el rol de usuarios
            $user->assignRole($validated['role']);
        });



        // Retornar hacia atras
        return back();
    }

    public function update(Request $request, User $user)
    {

        //Validar de esta forma si se va a colocar la password
        if($request->modify_password)
        {
            // crear la validacion
            $validated = $request->validate([
                'name' => ['required','string','min:4','max:75'],
                'email' => ['required','string','email','max:150',new CheckMaxUser(), Rule::unique('users', 'email')->ignore($user)],
                'password'=> ['required','string',Password::min(8),'confirmed'],
                'role' => ['required',Rule::enum(UserRoleEnum::class)],
            ]);
        }else{
            // crear la validacion
            $validated = $request->validate([
                'name' => ['required','string','min:4','max:75'],
                'email' => ['required','string','email','max:150',new CheckMaxUser(), Rule::unique('users', 'email')->ignore($user)],
                'role' => ['required',Rule::enum(UserRoleEnum::class)],
            ]);
        }
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

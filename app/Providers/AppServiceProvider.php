<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{




    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        //Verificar si es administrador
        Gate::define('is-admin', function (User $user) {

            //Verificar si es adminitrador
            if($user->role->value == 'admin')
            {
                //Permitir si es administrador
                return Response::allow();
            }else{
                //Denegar el acceso si no es adminitrador
                return Response::deny('No esta autorizado, comunicarse con el administrador');
            }

        });

        //Evitar envolver los datos
        JsonResource::withoutWrapping();


    }
}

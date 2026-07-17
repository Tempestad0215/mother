<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
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
//        Gate::define('is-admin', function (User $user) {
//
//            //Verificar si es adminitrador
//            if($user->role->value == 'admin')
//            {
//                //Permitir si es administrador
//                return Response::allow();
//            }else{
//                //Denegar el acceso si no es adminitrador
//                return Response::deny('No esta autorizado, comunicarse con el administrador');
//            }
//
//        });

        if (config('app.env') !== 'local' || str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        //Evitar envolver los datos
        JsonResource::withoutWrapping();


    }
}

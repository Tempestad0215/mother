<?php

namespace App\Providers;

use App\Models\Clients;
use App\Policies\ClientsPolicy;
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
        //Politica de cliente
        Gate::policy(Clients::class, ClientsPolicy::class);


    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ConfigServiceProvider::class, function ($app){
            return new ConfigServiceProvider($app);
        });
    }

    public function boot(): void
    {
    }
}

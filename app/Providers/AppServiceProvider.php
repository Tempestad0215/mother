<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Clients;
use App\Models\Product;
use App\Models\Supplier;
use App\Policies\CategoryPolicy;
use App\Policies\ClientsPolicy;
use App\Policies\ProductInPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SupplierPolicy;
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
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Supplier::class, SupplierPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Product::class, ProductInPolicy::class);


    }
}

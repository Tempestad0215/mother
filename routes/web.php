<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// La ruita de registro bloquerar


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/register',function(){
        return Inertia::render('Auth/Register');
    })->name('register');



    // Ruta de usuario
    Route::controller(UserController::class)
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::post('/', 'store')->name('store');
    });



    // Cliente
    Route::controller(ClientsController::class)
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/','create')->name('create');
        Route::post('/','store')->name('store');
        Route::get('/show','show')->name('show');
        Route::get('/edit/{client}','edit')->name('edit');
        Route::patch('/{client}','update')->name('update');
        Route::patch('/destroy/{client}','destroy')->name('destroy');
    });

    // Suplidoress
    Route::controller(SupplierController::class)
    ->prefix('supplier')
    ->name('supplier.')
    ->group(function(){
        // Route::get('/','create')->name('create');
        Route::post('/','store')->name('store');
        Route::get('/get','get')->name('get');

    });

    // Productos
    Route::controller(ProductController::class)
    ->prefix('product')
    ->name('product.')
    ->group(function(){
        Route::get('/show','show')->name ('show');
        Route::get('/','create')->name('create');
        Route::post('/','store')->name('store');
        Route::get('/edit/{product}','edit')->name('edit');
        Route::patch('/{product}','update')->name('update');
        Route::get('/get','get')->name('get');
    });

    //Entrada de los productos
    Route::controller(ProductInController::class)
        ->prefix('product-in')
        ->name('product-in.')
        ->group(function(){
            Route::get('/','index')->name('create');
            Route::patch('/{productIn}','store')->name('store');
            Route::get('/edit/{productIn}','edit')->name('edit');
            Route::patch('/update/{productIn}','update')->name('update');
            Route::patch('/destroy/{productIn}','destroy')->name('destroy');

        });

});

<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


// Ruta de usuario
Route::controller(UserController::class)
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::post('/', 'store')->name('store');
    });



// Empleado
Route::controller(ClientsController::class)
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/','create')->name('create');
        Route::post('/','store')->name('store');
        Route::get('/show','show')->name('show');
        Route::get('/edit/{client}','edit')->name('edit');
        Route::patch('/destroy/{client}','destroy')->name('destroy');
    });


// La ruita de registro bloquerar
Route::get('/register',function(){
    return Inertia::render('Auth/Register');
})->name('register')->middleware('auth:sanctum');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

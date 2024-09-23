<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductSaleController;
use App\Http\Controllers\SettingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;



// La ruita de registro bloquerar


//Route::get('/pass', function () {
//    return Hash::make('password');
//});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    /**
     * Ruta de Bienvenida
     */
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });


    /**
     * Configuracion de la app
     */
    Route::controller(SettingController::class)
        ->prefix('setting')
        ->name('setting.')
        ->group(function () {
       Route::get('/', 'index')
           ->middleware('can:is-admin')
           ->name('index');
       Route::post('/','store')->name('store');
    });



    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/register',function(Request $request){

        //Crear la instancia
        $userHelper = new UserHelper();

        //Obtener los datos desde el helpers
        $users = $userHelper->getUserPaginator($request);

        //Devolver la vista con los datos
        return Inertia::render('Auth/Register',[
            'users' => $users
        ]);
    })->name('register');

    /**
     * Usuarios
     */
    Route::controller(UserController::class)
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::post('/', 'store')->name('store');
        Route::patch('/{user}','update')->name('update');
        Route::patch('/destroy/{user}', 'destroy')->name('destroy');
    });

    /**
     * Cliente
     */
    Route::controller(ClientController::class)
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/','create')->name('create');
        Route::post('/','store')->name('store');
        Route::get('/show','show')->name('show');
        Route::get('/edit/{client}','edit')->name('edit');
        Route::patch('/{client}','update')->name('update');
        Route::patch('/destroy/{client}','destroy')->name('destroy');
        Route::get('/get','getJson')->name('get.json');
    });

    /**
     * Categoria
     */
    Route::controller(CategoryController::class)
        ->prefix('category')
        ->name('category.')
        ->group(function () {
            Route::get('/','create')->name('create');
            Route::post('/','store')->name('store');
            Route::patch('/{category}','update')->name('update');
            Route::patch('/destroy/{category}','destroy')->name('destroy');
            Route::get('/get','getJson')->name('get.json');
        });

    /**
     * Suplidores
     */
    Route::controller(SupplierController::class)
    ->prefix('supplier')
    ->name('supplier.')
    ->group(function(){
         Route::get('/','create')->name('create');
        Route::post('/','store')->name('store');
        Route::get('/get','getJson')->name('get.json');
        Route::patch('/{supplier}','update')->name('update');
        Route::patch('/destroy/{supplier}','destroy')->name('destroy');

    });

    /**
     * Productos
     */
    Route::controller(ProductController::class)
    ->prefix('product')
    ->name('product.')
    ->group(function(){
        Route::get('/','create')->name('create');
        Route::get('/show','show')->name ('show');
        Route::post('/','store')->name('store');
        Route::get('/edit/{product}','edit')->name('edit');
        Route::patch('/{product}','update')->name('update');
        Route::get('/get','get')->name('get');
        Route::get('/get/json','getJson')->name('get.json');
        Route::get('/get/code','getByCode')->name('get.code');
        Route::patch('/delete/{product}','destroy')->name('destroy');
    });

    /**
     * Ventas
     */
    Route::controller(ProductSaleController::class)
        ->prefix('product-sale')
        ->name('product-sale.')
        ->group(function(){
           Route::get('/','create')->name('create');
           Route::get('/get','getJson')->name('get.json');
           Route::post('/','store')->name('store');
           Route::patch('/{sale}','update')->name('update');
           Route::get('/show','show')->name('show');
           Route::patch('/item/destroy/{product}/{sale}','destroyItem')->name('destroy.item');
           Route::patch('/sale/destroy/{sale}/{inventoried}','destroySale')->name('destroy-sale');
        });

    /**
     * Entrada de productos
     */
    Route::controller(ProductInController::class)
        ->prefix('product-in')
        ->name('product-in.')
        ->group(function(){
            Route::get('/','index')->name('create');
            Route::get('show','show')->name('show');
            Route::patch('/{productIn}','store')->name('store');
            Route::get('/entrance/{productIn}','entrance')->name('entrance');
            Route::get('/entrance/edit/{trans}','edit')->name('edit');
            Route::patch('/update/{trans}','update')->name('update');
            Route::patch('/destroy/{trans}','destroy')->name('destroy');

        });

    /**
     * Reportes
     */
    Route::controller(ReportController::class)
        ->prefix('report')
        ->name('report.')
        ->group(function (){
           Route::get('/','index')->name('index');
           Route::get('/day','getDailyByDate')->name('day');
           Route::post('/daily','getDailyByDate')->name('getDailyByDate');
        });



});

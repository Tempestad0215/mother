<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SequenceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductSaleController;
use App\Http\Controllers\SettingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Setting;



// La ruita de registro bloquerar


//Route::get('/pass', function () {
//    return Hash::make('password');
//});

Route::middleware([
    'auth:sanctum',

    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    /*
     * Ruta de bienvenida
     */
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });


    /*
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



    //conseguir la configuracion
    $setting = Setting::pluck('sequence')->first();
    // Solo ver esto si la opcion esta habilitado
    if ($setting)
    {
        /*
     * Secuencia de RNC
     */
        Route::controller(SequenceController::class)
            ->prefix('setting/sequence')
            ->name('sequence.')
            ->group(function () {
                Route::get('/', 'create')->name('create');
                Route::get('/get/{type}','get')->name('get');
                Route::get('/get/rnc/{rnc}','getRnc')->name('getRnc');
                Route::post('/','store')->name('store');
                Route::get('/{sequence}','edit')->name('edit');
                Route::delete('/{sequence}','destroy')->name('destroy');
            });
    }




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

    /*
     * Usuario
     */
    Route::controller(UserController::class)
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::post('/', 'store')->name('store');
        Route::patch('/{user}','update')->name('update');
        Route::patch('/destroy/{user}', 'destroy')->name('destroy');
    });

    /*
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

    /*
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



    /*
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




    /*
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


    /*
     * Ventas
     */
    Route::controller(ProductSaleController::class)
        ->prefix('sale')
        ->name('sale.')
        ->group(function(){
           Route::get('/','create')->name('create');
           Route::get('/get','getJson')->name('get.json');
           Route::get('/show','show')->name('show');
           Route::get('/credit-note/{sale}','creditNote')->name('credit.note');
           Route::post('/','store')->name('store');
           Route::patch('/credit-note/{sale}','creditNoteStore')->name('credit.note.store');
           Route::patch('/update/{sale}','update')->name('update');
           Route::patch('/item/destroy/{product}/{sale}','destroyItem')->name('destroy.item');
           Route::patch('/destroy/{sale}/{inventoried}','destroySale')->name('destroy-sale');
        });




    /*
     * Ventas
     */
    Route::controller(ProductInController::class)
        ->prefix('in')
        ->name('in.')
        ->group(function(){
            Route::get('/','index')->name('create');
            Route::get('show','show')->name('show');
            Route::patch('/{productIn}','store')->name('store');
            Route::get('/entrance/{productIn}','entrance')->name('entrance');
            Route::get('/entrance/edit/{trans}','edit')->name('edit');
            Route::patch('/update/{trans}','update')->name('update');
            Route::patch('/destroy/{trans}','destroy')->name('destroy');

        });

    /*
     * Reportes
     */
    Route::controller(ReportController::class)
        ->prefix('report')
        ->name('report.')
        ->group(function (){
           Route::get('/','index')->name('index');
            Route::get('/day','getDay')->name('day');
           Route::get('/day/date','getDailyByDate')->name('day.date');
           Route::get('/product/low','stockLow')->name('product.low');
           Route::post('/daily','getDailyByDate')->name('getDailyByDate');
        });



});

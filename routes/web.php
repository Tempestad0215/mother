<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\CashReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportSaleController;
use App\Http\Controllers\SequenceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductSaleController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\IsAdminMiddleware;
use App\Models\Sale;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\CreditNoteController;



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
           ->name('index');
       Route::post('/','store')->name('store');
    });

    /*
     * Secuencia de RNC
     */
    Route::middleware([IsAdminMiddleware::class])->controller(SequenceController::class)
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

    /**
     * Dashboard de la app
     */
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
     * Usuario
     */
    Route::middleware([IsAdminMiddleware::class])->controller(UserController::class)
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
        Route::get('/get','getJson')->name('get.json');
        Route::get('/show','show')->name('show');
        Route::get('/edit/{client}','edit')->name('edit');
        Route::post('/','store')->name('store');
        Route::patch('/{client}','update')->name('update');
        Route::delete('/destroy/{client}','destroy')->name('destroy');

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
         Route::get('/show','show')->name('show');
        Route::get('/get','getJson')->name('get.json');
         Route::get('/{supplier}','edit')->name('edit');
         Route::post('/','store')->name('store');
         Route::patch('/{supplier}','update')->name('update');
         Route::delete('/destroy/{supplier}','destroy')->name('destroy');

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
    ->prefix('sale')
    ->name('sale.')
    ->group(function(){
       Route::get('/','create')->name('create');
       Route::get('/get','getJson')->name('get.json');
       Route::get('/show','show')->name('show');
       Route::get('/test/invoice', 'testInvoice')->name('test-invoice');
       Route::post('/counter','counterPost')->name('counterPost');
       Route::post('/','store')->name('store');
       Route::patch('/update/{sale}','update')->name('update');
       Route::patch('/item/destroy/{product}/{sale}','destroyItem')->name('destroy.item');
       Route::patch('/destroy/{sale}/{inventoried}','destroySale')->name('destroy-sale');
    });
    /*
     * Repotes de las ventas
     */
    Route::controller(CashReportController::class)
        ->prefix('sale/report')
        ->name('sale.report.')
        ->group(function(){
           Route::get('/','index')->name('index');
           Route::post('/','store')->name('store');
           Route::get('/print','get')->name('get');
        });

    /**
     * Notas de credito o devoluciones
     *
     */
    Route::controller(CreditNoteController::class)
    ->prefix('credit-note')
    ->name('credit-note.')
    ->group(function (){
        Route::get('/','index')->name('index');
        Route::get('/show', 'show')->name('show');
        Route::get('/get/balance/{code}','getBalance')->name('balance');
        Route::get('/get/{code}','creditNoteGet')->name('get');
        Route::patch('/{sale}','store')->name('store');
    });

    /**
     *
     * Entradas
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

    /**
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

    /**
     * Reporte de Ventas
     */
    Route::controller(ReportSaleController::class)
    ->prefix('report/sale')
    ->name('report-sale.')
    ->group(function (){
       Route::get('/','index')->name('index');
       Route::get('/json','reportSaleRange')->name('range');
    });

    /**
     * Facturas del sistema
     */
    Route::controller(InvoiceController::class)
    ->prefix('invoice')
    ->name('invoice.')
    ->group(function (){
       Route::get('/belt/sale/{sale}','beltSale')->name('belt.sale');
       Route::get('/belt/note/{creditNote}','beltNote')->name('belt.note');
       Route::get('/getA/{sale}','getA')->name('getA');
    });


  /*
   *Monedas
   */
    Route::controller(CurrencyController::class)
    ->prefix('currency')
    ->name('currency.')
    ->group(function (){
        Route::get('/check-exchange','checkExchange')->name('check');
        Route::get('/getExchange/{month}/{year}','getExchange')->name('get.exchange');
        Route::post('/exchange','exchangeStore')->name('exchange.store');
        Route::delete('/{currency}','destroy')->name('destroy');
        Route::put('/restore/{currency}','restore')->name('restore');
    });


    /*
     * Compra
     */
    Route::controller(PurchaseController::class)
    ->prefix('purchase')
    ->name('purchase.')
    ->group(function (){
        Route::get('/','index')->name('index');
    });


    Route::get('/test', function () {
        return \Spatie\LaravelPdf\Facades\Pdf::view('pdfs.report.sale')
            ->margins(2,2,2,2);
    });


});

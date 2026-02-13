<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\AccontCoController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportSaleController;
use App\Http\Controllers\SequenceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WarehouseController;
use App\Http\Middleware\IsAdminMiddleware;
use App\Pdfs\ProductLabelV1;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\InventoryMovementController;
use Spatie\Permission\Models\Role;

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
            Route::post('/', 'store')->name('store');
        });

    /*
     * Secuencia de RNC
     */
    Route::middleware([IsAdminMiddleware::class])->controller(SequenceController::class)
        ->prefix('setting/sequence')
        ->name('sequence.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::get('/get/{type}', 'get')->name('get');
            Route::get('/get/rnc/{rnc}', 'getRnc')->name('getRnc');
            Route::post('/', 'store')->name('store');
            Route::get('/{sequence}', 'edit')->name('edit');
            Route::delete('/{sequence}', 'destroy')->name('destroy');
        });

    /*
     * Dashboard de la app
     */
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/register', function (Request $request) {

        //Crear la instancia
        $userHelper = new UserHelper();

        //Obtener los datos desde el helpers
        $users = $userHelper->getUserPaginator($request);

        //Devolver la vista con los datos
        return Inertia::render('Auth/Register', [
            'users' => $users,
            'roles' => Role::all()
        ]);
    })->name('register')
        ->middleware(['role:Super Admin|Supervisor']);

    /*
     * Usuario
     */
    Route::controller(UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::post('/', 'store')->name('store');
            Route::patch('/{user}', 'update')->name('update');
            Route::patch('/destroy/{user}', 'destroy')->name('destroy');
        });

    /*
     * Cliente
     */
    Route::controller(ClientController::class)
        ->prefix('client')
        ->name('client.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::get('/get', 'getJson')->name('get.json');
            Route::get('/show', 'show')->name('show');
            Route::get('/edit/{client}', 'edit')->name('edit');
            Route::post('/', 'store')->name('store');
            Route::patch('/{client}', 'update')->name('update');
            Route::delete('/destroy/{client}', 'destroy')->name('destroy');
            Route::get('/download', 'exportExcel')->name('export-excel')
                ->withoutMiddleware(VerifyCsrfToken::class);

        });

    /*
     * Categoria
     */
    Route::controller(CategoryController::class)
        ->prefix('category')
        ->name('category.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::get('/download', 'exportExcel')->name('export-excel')
                ->withoutMiddleware(VerifyCsrfToken::class);
            Route::get('/{category}', 'edit')->name('edit');
            Route::post('/', 'store')->name('store');
            Route::patch('/{category}', 'update')->name('update');
            Route::delete('/destroy/{category}', 'destroy')->name('destroy');
            Route::get('/get', 'getJson')->name('get.json');

        });

    /*
     * Suplidore
     */
    Route::controller(SupplierController::class)
        ->prefix('supplier')
        ->name('supplier.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::get('/show', 'show')->name('show');
            Route::get('/get', 'getJson')->name('get.json');
            Route::get('/dowmload', 'exportExcel')->name('export-excel');
            Route::get('/{supplier}', 'edit')->name('edit');
            Route::post('/', 'store')->name('store');
            Route::patch('/{supplier}', 'update')->name('update');
            Route::delete('/destroy/{supplier}', 'destroy')->name('destroy');

        });

    /*
     * Productos
     */
    Route::controller(ProductController::class)
        ->prefix('product')
        ->name('product.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::get('/show', 'show')->name('show');
            Route::post('/', 'store')->name('store');
            Route::get('/edit/{product}', 'edit')->name('edit');
            Route::get('/get-label/{code}', 'createLabel')->name('get-label');
            Route::get('/get', 'get')->name('get');
            Route::get('/in', 'in')->name('in');
            Route::get('/get/json', 'getJson')->name('get.json');
            Route::get('/get/code', 'getByCode')->name('get.code');
            Route::patch('/update/{product}', 'update')->name('update');
            Route::patch('/delete/{product}', 'destroy')->name('destroy');

        });


    // Movimiento de inventario de productos
    Route::controller(InventoryMovementController::class)
        ->prefix('product/entry')
        ->name('entry.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{entry}', 'edit')->name('edit');
            Route::post('/', 'entry')->name('store');
            Route::patch('/{entry}', 'update')->name('update');
            Route::delete('/{entry}', 'destroy')->name('destroy');
        });


    /*
     * Ventas
     */
    Route::controller(SaleController::class)
        ->prefix('sale')
        ->name('sale.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::get('/get', 'getJson')->name('get.json');
            Route::get('/show', 'show')->name('show');
            Route::get('/close', 'close')->name('close');
            Route::post('/close/get', 'getClose')->name('get.close');
            Route::get('/test/invoice', 'testInvoice')->name('test-invoice');
            Route::get('/counter', 'counter')->name('counter');
            Route::post('/counter', 'counterPost')->name('counterPost');
            Route::post('/', 'store')->name('store');
            Route::patch('/update/{sale}', 'update')->name('update');
            Route::patch('/item/destroy/{product}/{sale}', 'destroyItem')->name('destroy.item');
            Route::patch('/destroy/{sale}/{inventoried}', 'destroySale')->name('destroy-sale');
        });


    /*
     * Repotes de las ventas
     */
    Route::controller(ExchangeController::class)
        ->prefix('sale/report')
        ->name('sale.report.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });

    /*
     * Notas de credito o devoluciones
     */
    Route::controller(CreditNoteController::class)
        ->prefix('sale/credit-note')
        ->name('credit-note.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show', 'show')->name('show');
            Route::get('/get/balance/{code}', 'getBalance')->name('balance');
            Route::get('/get/{code}', 'creditNoteGet')->name('get');
            Route::patch('/{sale}', 'store')->name('store');
        });


    /*
     * Reportes
     */
    Route::controller(ReportController::class)
        ->prefix('report')
        ->name('report.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/day', 'getDay')->name('day');
            Route::get('/day/date', 'getDailyByDate')->name('day.date');
            Route::get('/product/low', 'stockLow')->name('product.low');
            Route::post('/daily', 'getDailyByDate')->name('getDailyByDate');
        });

    /*
     * Reporte de Ventas
     */
    Route::controller(ReportSaleController::class)
        ->prefix('report/sale')
        ->name('report-sale.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/json', 'reportSaleRange')->name('range');
        });

    /*
     * Facturas del sistema
     */
    Route::controller(InvoiceController::class)
        ->prefix('invoice')
        ->name('invoice.')
        ->group(function () {
            Route::get('/belt/sale/{sale}', 'beltSale')->name('belt.sale');
            Route::get('/belt/note/{creditNote}', 'beltNote')->name('belt.note');
            Route::get('/getA/{sale}', 'getA')->name('getA');
            Route::get('/getB/{counter}', 'getB')->name('getB');
            Route::get('/label/{product}', 'label')->name('label');
        });


    /*
    *Monedas
    */
    Route::controller(AccontCoController::class)
        ->prefix('/setting/currency')
        ->name('currency.')
        ->group(function () {
            Route::get('/check-exchange', 'checkExchange')->name('check');
            Route::get('/getExchange/{month}/{year}', 'getExchange')->name('get.exchange');
            Route::post('/exchange', 'exchangeStore')->name('exchange.store');
            Route::delete('/{currency}', 'destroy')->name('destroy');
            Route::put('/restore/{currency}', 'restore')->name('restore');
        });



    /*
     * Compra
     */
    Route::controller(PurchaseController::class)
        ->prefix('purchase')
        ->name('purchase.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/show', 'show')->name('show');
            Route::get('/receive', 'receive')->name('receive');
            Route::get('/output', 'output')->name('output');
            Route::patch('/{purchase}/cancel','cancel')->name('cancel');
            Route::patch('/{purchase}/approve', 'approve')->name('approve');
        });

    Route::controller(ReceivingController::class)
        ->prefix('purchase/receiving')
        ->name('purchase.receiving.')
        ->group(function () {
            Route::get('/{supplier}', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });

    /*
     * cuentas contables modification y demas
     */
    Route::controller(AccontCoController::class)
        ->prefix('/setting/aco')
        ->name('aco.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{aco}', 'update')->name('update');
            Route::delete('/{aco}', 'destroy')->name('destroy');
        });

    /*
     * Alamceneces
     */
    Route::controller(WarehouseController::class)
        ->prefix('/setting/warehouse')
        ->name('wh.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{wh}', 'update')->name('update');
            Route::delete('/{wh}', 'destroy')->name('destroy');
        });


    Route::controller(UnitController::class)
        ->prefix('/setting/unit')
        ->name('unit.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{unit}', 'update')->name('update');
            Route::delete('/{unit}', 'destroy')->name('destroy');
        });


    Route::controller(TaxController::class)
        ->prefix('/setting/tax')
        ->name('tax.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{tax}', 'update')->name('update');
            Route::delete('/{tax}', 'destroy')->name('destroy');
        });

    Route::controller(BrandController::class)
        ->prefix('/setting/branch')
        ->name('branch.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{branch}', 'update')->name('update');
            Route::delete('/{branch}', 'destroy')->name('destroy');
        });
//

    Route::get('/test', function () {

        $pdf = new ProductLabelV1();

        $pdf->createInfo();

        $pdf->Output('test_pdf.pdf');

    })->name('printTest');

//    Route::get('/sale', function (){
//        $sale = Sale::first();
//
//
//        $pdf = new \App\Invoices\Ticket80($sale);
//
//        return $pdf->output('ticket80.pdf');
//
//    });
//
//
//    Route::get('/credit', function (){
//        $sale = \App\Models\CreditNote::first();
//
//
//        $pdf = new \App\Invoices\Ticket80($sale);
//
//        return $pdf->output('credit.pdf');
//
//    });


});

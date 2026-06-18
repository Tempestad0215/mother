<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\AccontCoController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OutController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportSaleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SequenceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Middleware\IsAdminMiddleware;
use App\Models\PurchaseReceipts;
use App\Models\Setting;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Ruta de bienvenida
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Register users list (mantener por ser especial)
    Route::get('/register', function (Request $request) {
        $userHelper = new UserHelper();
        $users = $userHelper->getUserPaginator($request);

        return Inertia::render('Auth/Register', [
            'users' => $users,
            'roles' => Role::all()
        ]);
    })->name('register')->middleware(['role:Super Admin|Supervisor']);

    /*
     * Configuración de la app
     */
    Route::apiResource('setting', SettingController::class)->only(['index', 'store']);

    /*
     * Secuencia de RNC - Solo para admin
     */
    Route::middleware([IsAdminMiddleware::class])->prefix('setting/sequence')->name('sequence.')->group(function () {
        Route::get('/', [SequenceController::class, 'create'])->name('create');
        Route::get('/get/{type}', [SequenceController::class, 'get'])->name('get');
        Route::get('/get/rnc/{rnc}', [SequenceController::class, 'getRnc'])->name('getRnc');
        Route::post('/', [SequenceController::class, 'store'])->name('store');
        Route::get('/{sequence}', [SequenceController::class, 'edit'])->name('edit');
        Route::delete('/{sequence}', [SequenceController::class, 'destroy'])->name('destroy');
    });

    /*
     * Usuarios - Custom porque no sigue completamente REST
     */
    Route::prefix('user')->name('user.')->group(function () {
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::patch('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    /*
     * Rutas adicionales de compras (no estándar)
     */
    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('/show', [PurchaseController::class, 'show'])->name('show');
        Route::get('/receive', [PurchaseController::class, 'receive'])->name('receive');
        Route::get('/output', [PurchaseController::class, 'output'])->name('output');
        Route::patch('/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('cancel');
        Route::patch('/{purchase}/approve', [PurchaseController::class, 'approve'])->name('approve');
    });


    /**
     *
     */
    Route::prefix('client')->name('client.')->controller(ClientController::class)->group(function () {
       Route::get('/get-json', 'getJson')->name('get.json');
    });

    /*
     * Resources principales
     */
    Route::apiResources([
        'client' => ClientController::class,
        'category' => CategoryController::class,
        'supplier' => SupplierController::class,
        'product' => ProductController::class,
        'sale' => SaleController::class,
        'purchase' => PurchaseController::class,
        'price-list' => PriceListController::class,
        'setting/warehouse' => WarehouseController::class,
        'aco' => AccontCoController::class,
        'unit' => UnitController::class,
        'tax' => TaxController::class,
        'branch' => BrandController::class,
        'entry' => EntryController::class,
        'out' => OutController::class
    ]);

    Route::get('price-list/product/{product}',[PriceListController::class,'productShow'])
        ->name('price-list.product.show');

    /*
     * Resources con personalización
     */
    Route::apiResource('inventory-movement', InventoryMovementController::class)
        ->parameter('inventory-movement', 'entry')
        ->names([
            'index' => 'entry.index',
            'store' => 'entry.store',
            'show' => 'entry.show',
            'update' => 'entry.update',
            'destroy' => 'entry.destroy',
        ]);

    /*
     * Rutas adicionales para productos (no estándar REST)
     */
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/get-label/{code}', [ProductController::class, 'createLabel'])->name('get-label');
        Route::get('/get', [ProductController::class, 'get'])->name('get');
        Route::get('/get/json', [ProductController::class, 'getJson'])->name('get.json');
        Route::get('/get/code', [ProductController::class, 'getByCode'])->name('get.code');
        Route::patch('/delete/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    /*
     * Rutas específicas de ventas
     */
    Route::prefix('sale')->name('sale.')->group(function () {
        Route::get('/refund/{code}', [SaleController::class, 'refund'])->name('refund');
        Route::get('/close', [SaleController::class, 'close'])->name('close');
        Route::post('/close/get', [SaleController::class, 'getClose'])->name('get.close');
        Route::get('/counter', [SaleController::class, 'counter'])->name('counter');
        Route::patch('/item/destroy/{product}/{sale}', [SaleController::class, 'destroyItem'])->name('destroy.item');
        Route::patch('/destroy/{sale}/{inventoried}', [SaleController::class, 'destroySale'])->name('destroy-sale');
    });

    /*
     * Reportes de ventas/Exchange
     */
    Route::apiResource('sale/report', ExchangeController::class)
        ->parameter('sale/report', 'exchange')
        ->names('sale.report.');

    /*
     * Notas de crédito
     */
    Route::prefix('sale/credit-note')->name('credit-note.')->group(function () {
        Route::get('/', [CreditNoteController::class, 'index'])->name('index');
        Route::get('/show', [CreditNoteController::class, 'show'])->name('show');
        Route::get('/get/balance/{code}', [CreditNoteController::class, 'getBalance'])->name('balance');
        Route::get('/get/{code}', [CreditNoteController::class, 'creditNoteGet'])->name('get');
        Route::post('/{sale}', [CreditNoteController::class, 'store'])->name('store');
    });

    /*
     * Reportes generales
     */
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/day', [ReportController::class, 'getDay'])->name('day');
        Route::get('/day/date', [ReportController::class, 'getDailyByDate'])->name('day.date');
        Route::get('/product/low', [ReportController::class, 'stockLow'])->name('product.low');
        Route::post('/daily', [ReportController::class, 'getDailyByDate'])->name('getDailyByDate');
    });

    /*
     * Reporte de ventas
     */
    Route::get('report/sale', [ReportSaleController::class, 'index'])->name('report-sale.index');
//    Route::get('report/sale/json', [ReportSaleController::class, 'reportSaleRange'])->name('report-sale.range');


    /*
     * Facturas
     */
    Route::prefix('invoice')->controller(InvoiceController::class)
        ->name('invoice.')->group(function () {
        Route::get('/sale/{sale}', 'getSaleInvoice')->name('sale');
        Route::get('/credit-note/{creditNote}', 'getCreditNoteInvoice')->name('credit-note');
        Route::get('/belt/sale/{sale}', 'beltSale')->name('belt.sale');
        Route::get('/belt/note/{creditNote}',  'beltNote')->name('belt.note');
        Route::get('/getA/{sale}',  'getA')->name('getA');
//        Route::get('/getB/{counter}',  'getB'])->name('getB');
        Route::get('/label/{product}',  'label')->name('label');
    });

    /*
     * Configuraciones (Settings)
     */
    Route::prefix('setting')->name('setting.')->group(function () {
        // Monedas/Exchange
        Route::controller(AccontCoController::class)->group(function () {
            Route::get('/currency', 'index')->name('currency.index');
            Route::post('/currency', 'store')->name('currency.store');
            Route::get('/currency/check-exchange', 'checkExchange')->name('currency.check');
            Route::get('/currency/getExchange/{month}/{year}', 'getExchange')->name('currency.get.exchange');
            Route::post('/currency/exchange', 'exchangeStore')->name('currency.exchange.store');
            Route::delete('/currency/{currency}', 'destroy')->name('currency.destroy');
            Route::put('/currency/restore/{currency}', 'restore')->name('currency.restore');
        });

    });

    /*
     * Recepción de compras
     */
    Route::prefix('purchase/receiving')->name('purchase.receiving.')->group(function () {
        Route::get('/{supplier}', [ReceivingController::class, 'index'])->name('index');
        Route::post('/', [ReceivingController::class, 'store'])->name('store');
    });



    // Test route
    Route::get('/test', function () {

        $purchase_receipts = PurchaseReceipts::first('*');
        $setting = Setting::latest('created_at')->first();

        if (!file_exists(storage_path('app/public/pdfs/receptions'))) {
            mkdir(storage_path('app/public/pdfs/receptions'), 0777, true);
        }

        return \Spatie\LaravelPdf\Facades\Pdf::view('pdfs.purchase.reception.v1',[
            'receipts' => $purchase_receipts,
            'setting' => $setting
        ])
            ->format('letter')
            ->headerView('pdfs.headers.header-reception-v1',[
                'receipts'=> $purchase_receipts,
                'setting' => $setting
            ])
            ->footerView('pdfs.footers.footer-reception-v1',[
                'receipts' => $purchase_receipts,
                'setting' => $setting
            ])
            ->name('test.pdf');
    })->name('printTest');


    Route::get('/test/2', function (){

        $product = \App\Models\Product::first();

        $labelTemplate = view('pdfs.ticket.label',[
            'code' => $product->code
        ])->render();

        $response = \Illuminate\Support\Facades\Http::attach('index.hmtl', $labelTemplate, 'index.html')
            ->post("http://localhost:3100/forms/chromium/convert/html",[
                'paperWidth' => '3.14',  // 80mm en pulgadas
                'paperHeight' => '1.5',   // Alto estimado de página corta
                'marginLeft' => '0.1',
                'marginRight' => '0.1',
                'marginTop' => '0.1',    // Espacio para la cabecera fija
                'marginBottom' => '0.1',
                'waitDelay' => '600ms',  // Tiempo para que cargue Tailwind 4 por CDN
            ]);

        if ($response->successful()){
            return response($response->body(),200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="ticket.pdf"',
            ]);
        }
        return response()->json(['error' => 'No se pudo conectar con Gotenberg'], 500);
    });
});

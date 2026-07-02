<?php

namespace App\Http\Controllers;

use App\Http\Resources\DTopProductResource;
use App\Http\Resources\DWarehouseStockLowResource;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{


    /**
     * @return Response
     */
    public function index()
    {

        return Inertia::render('Dashboard',[
            'productsLowStock' => $this->stockLow(),
            'topProduct' => $this->topProduct(),
            'kpis' => $this->kpis(),
        ]);
    }


    /**
     * @return AnonymousResourceCollection
     */
    private function stockLow()
    {
        // 2. Alertas: Productos con stock bajo (Ajusté el límite a 10 como tienes en tu lógica)
        // Consultamos desde los Almacenes
        $warehouses = Warehouse::whereHas('products', function ($query) {
            // Filtramos el stock crítico en la tabla pivote
            $query->where('warehouse_products.stock_quantity', '<=', 10);
        })
            ->with(['products' => function ($query) {
                // Cargamos solo los productos que cumplen la condición de stock bajo
                $query->where('warehouse_products.stock_quantity', '<=', 10)
                    ->where('products.status', true);
            }])
            ->get();
        return DWarehouseStockLowResource::collection($warehouses);

    }


    /**
     * @return AnonymousResourceCollection
     */
    private function topProduct()
    {
        $data = Product::where('status', true)
            ->withSum('saleItem as total_qty', 'stock')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        return DTopProductResource::collection($data);
    }


    /**
     * @return array
     */
    private function kpis(): array
    {
        $hoy = Carbon::today();

        // 1. Métricas clave del día (KPI)
        return [
            'total_sales' => (float) Sale::whereDate('created_at', $hoy)
                ->where('status', true) // Solo ventas activas
                ->sum('amount'),

            'transactions_count' => Sale::whereDate('created_at', $hoy)->count(),

            'total_refunds' => (float) CreditNote::whereDate('created_at', $hoy)->sum('amount'),
        ];
    }
}

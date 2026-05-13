<?php

namespace App\Http\Controllers;

use App\Dtos\InventoryDto;
use App\Dtos\PriceListProductDto;
use App\Dtos\ProductDto;
use App\Enums\ProductTypeEnum;
use App\Helpers\GeneralHelper;
use App\Helpers\PriceListProductHelper;
use App\Helpers\ProductInventoryHelper;
use App\Helpers\TaxHelper;
use App\Helpers\WarehouseProductHelper;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSupplierResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Pdfs\ProductLabelV1;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;


class ProductController extends Controller implements HasMiddleware
{

    /**
     * Para controlar
     * @return Middleware[]
     */
    public static function middleware()
    {
        return [
            new Middleware('auth'),
            new Middleware('role:Super Admin|Supervisor'),
        ];
    }

    /**
     * @param PaginationRequest $request
     * @return RedirectResponse|Response
     */
    public function index(PaginationRequest $request): Response|RedirectResponse
    {

        $search = $request->input('search');
        $perPage = $request->input('per_page');
        $queryProduct = Product::query()->with(['price_list','brand','warehouses']);
        if (!empty($search)) {
            $queryProduct->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');

        }

        $product_paginated = $queryProduct->simplePaginate($perPage);
        $product_paginated->withQueryString();
        $products = ProductResource::collection($product_paginated);


        $productType = collect(ProductTypeEnum::cases())->mapWithKeys(fn(ProductTypeEnum $item) => [$item->name => $item->value])->toArray();

        //Verificar si existe configuración
        $setting = Setting::first();

        //si existe la configuración
        if (isset($setting)) {


            //Devolver correctamente
            return Inertia::render('Products/Register', [
                'products' => $products,
                'categories' => Category::orderBy('name')->get(),
                'suppliers' => Supplier::orderBy('company_name')->get(),
                'warehouse' => Warehouse::all(),
                'paymentTypes' => GeneralHelper::getPaymentTypes(),
                'productType' => $productType,
                'branches' => Brand::all(),
                'units' => Unit::all(),
                'taxes' => Tax::all(),
                'warehouses' => Warehouse::all(),
                'priceLists' => PriceList::all(),
            ]);

        } else {

            Inertia::flash('message', 'Por favor, debe crear la setting primero');
            //Redirigir a la ventana de setting
            return to_route('setting.index');
        }

    }

    /**
     * Summary of store
     * @param StoreProductRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {

        //Para asegurar que no se guarda si hay problema
        DB::transaction(function () use ($request) {
            // Transformar los datos
            $product_dto = ProductDto::fromArray($request->validated());
            // Crear los datos de productos
            $product = Product::create($product_dto->toArray());
            // Tomar los datos de warehouseProduct y asinar al productos
            WarehouseProductHelper::upSert($product_dto->warehouse_product, $product);
            // Trasnformar los datos
            $data_price = new PriceListProductDto(
                $product->uuid,
                $product_dto->price_list_uuid,
                $product_dto->price,
                $product_dto->min_price,
                $product_dto->special_price

            );

            // Craer la lista de precios
            PriceListProductHelper::upSert($data_price, $product);
        });

        // Devolver hacia atras
        return back();

    }

    /**
     * Summary of show
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {

        // Realizar la busqueda
        $data = $this->get($request);

        //Devolver la vista con los datos

        return Inertia::render('Products/Show', [
            'products' => $data
        ]);

    }

    /**
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function edit(Product $product, Request $request): Response
    {
        $dataProducts = $this->get($request);
        $dataEdit = new ProductSupplierResource($product);

        return Inertia::render('Products/Register', [
            'productEdit' => $dataEdit,
            'products' => $dataProducts,
            'update' => true,
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
            'warehouse' => Warehouse::all()
        ]);

    }

    /**
     * @param StoreProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {

        DB::transaction(function () use ($request, $product) {
            // Actualizar los datos validados
            $product_dto = ProductDto::fromArray($request->validated());

            // Actualizar los datos de productos
            $product->update($product_dto->toArray());

//            Actualizar los datos de
            // Tomar los datos de warehouseProduct y asinar al productos
            WarehouseProductHelper::upSert($product_dto->warehouse_product, $product);
            // Trasnformar los datos
            $data_price = new PriceListProductDto(
                $product->uuid,
                $product_dto->price_list_uuid,
                $product_dto->price,
                $product_dto->min_price,
                $product_dto->special_price

            );

            // Craer la lista de precios
            PriceListProductHelper::upSert($data_price, $product);
        });


        // devolver hacia atrás
        return back();
    }

    /**
     * @param Product $product
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Product $product): RedirectResponse
    {



       //Actualizer los datos
        DB::transaction(function () use ($product) {
            // Tomar la varible de verificacion
            $hasStock = WarehouseProductHelper::checkStockForProduct($product);

            // Verificar si tien stock disponible
            if($hasStock){
                throw new \Exception("No se puede eliminar el producto, ya que tiene stock disponible.");
            }

            // Elimianr el producto
            $product->delete();
        });


        //Devolver atras
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getByCode(Request $request): JsonResponse
    {
        //conseguir los datos a buscar
        $search = $request->input('search');


        //Buscar los datos
        $data = Product::where('status', true)
            ->where(function ($query) use ($request, $search) {
                $query->where('id', $search)
                    ->orWhere('code', $search)
                    ->orWhere('bar_code', $search);
            })->firstOrFail();


        //DEvolver los datos
        return response()->json($data);
    }


//    public function createLabel()
//    {
//
//
//    }

    /**
     * @return Response
     */
    public function in(): Response
    {

        return Inertia::render('Products/In');

    }

    // Conseguir los productos

    /**
     * Summary of get
     * @param Request $request
     * @return Paginator
     */
    public function get(Request $request): Paginator
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|min:1|max:100',
        ]);

        $search = $validated['search'] ?? '';
        $perPage = $validated['perPage'] ?? 15;

        return Product::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ILIKE', "%$search%")
                        ->orWhere('description', 'ILIKE', "%$search%")
                        ->orWhere('sku', 'ILIKE', "%$search%");
                });
            })
            ->where('status', true)
            ->simplePaginate($perPage);

    }


    public function createLabel(string $code)
    {
        $fileName = "$code-label.pdf";
        $filePath = \Storage::disk('labels')->path($fileName);
        $pdf = new ProductLabelV1();
        $pdf->createInfo($code);
        $pdf->Output($filePath, 'F');


        $url = asset("storage/pdfs/labels/$fileName");

        if (!Storage::disk('labels')->exists($fileName)) {
            abort(404, 'No existe el label');
        }

        return \response()->json([
            'url' => $url,
        ]);

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getJson(Request $request): JsonResponse
    {

        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);
        //Buscar los datos
        $search = $request->input('search');


        // Tomar los datos
        $products = Product::where(function ($query) use (&$search) {
            $query->orWhere("name", "ILIKE", "%$search%")
                ->orWhere("description", "ILIKE", "%$search%");
        })->where("status", true)
            ->orderBy("name")
            ->take(15)
            ->get();

        //tomar los datos
        return response()->json($products);
    }


}

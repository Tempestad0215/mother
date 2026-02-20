<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Helpers\GeneralHelper;
use App\Http\Requests\PaginationRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductSupplierResource;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Pdfs\ProductLabelV1;
use Carbon\Carbon;
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
use Spatie\LaravelPdf\Facades\Pdf;
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
    public function create(PaginationRequest $request): Response|RedirectResponse
    {
        //Obtener los datos de los productos
        $data = $this->get($request);

        $search = $request->get('search');
        $perPage = $request->get('per_page');
        $queryProduct = Product::query();
        if (!empty($search)) {
            $queryProduct->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');

        }
        $products = $queryProduct->paginate($perPage)->withQueryString();


        $productType = collect(ProductTypeEnum::cases())->mapWithKeys(fn (ProductTypeEnum $item) => [$item->name => $item->value])->toArray();

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
                'nextProduct' => Product::generateCode() ?? null,
                'paymentTypes' => GeneralHelper::getPaymentTypes(),
                'productType' => $productType,
                'branches' => Brand::all(),
                'units' => Unit::all(),
                'taxes' => Tax::all(),
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

            $tax_id = $request->input('tax_id');
            $tax = Tax::find($tax_id);

            $data = $request->validated();

            //Guardar los datos de los productos
            $data['tax_rate'] = $tax->rate / 100;
            $data['tax'] = $data['price'] * $data['tax_rate'];

            $product = Product::create($data);


            // Guardar los datos de los productos
            if ($request->input('type') === 'servicio') {
                //Actualizar datos por fuera cuando son servicio
                $product->inventoried = false;
                $product->unit = "N/A";
                $product->tax = $request->input('tax_rate') / 100;
                $product->save();
            }
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
     */
    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {

        // Actualizar los datos validados
        $product->update($request->validated());

        //Actualizar estos datos si es servicios
        if ($request->get('type') === 'servicio') {
            //Actualizar datos por fuera cuando son servicio
            $product->inventoried = false;
            $product->price = $request->get('price');
            $product->unit = "N/A";
            $product->tax = $request->get('tax_rate') / 100;
            $product->save();
        }

        // devolver hacia atras
        return back();
    }

    /**
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {


        //Actulizar los datos
        $product->deleted_at = Carbon::now();
        $product->save();

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
        $search = $request->get('search');


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
                    $query->where('id', 'LIKE', "%$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('sku', 'LIKE', "%$search%");
                });
            })
            ->where('status', true)
            ->latest('id')
            ->simplePaginate($perPage);

    }


    public function createLabel(string $code)
    {
        $fileName = "{$code}-label.pdf";
        $filePath = \Storage::disk('labels')->path($fileName);
        $pdf = new ProductLabelV1();
        $pdf->createInfo($code);
        $pdf->Output($filePath, 'F');


        $url = asset("storage/pdfs/labels/{$fileName}");

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
        $search = $request->get('search');


        // Tomar los datos
        $products = Product::where(function ($query) use (&$search) {
            $query->orWhere("name", "LIKE", "%$search%")
                ->orWhere("description", "LIKE", "%$search%");
        })->where("status", true)
            ->orderBy("name")
            ->take(15)
            ->get();

        //tomar los datos
        return response()->json($products);
    }


}

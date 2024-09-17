<?php

namespace App\Http\Controllers;

use App\Helpers\CategoryHelper;
use App\Helpers\SupplierHelper;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProSupRes;
use App\Models\Setting;
use App\Services\configService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{

    private CategoryHelper $categoryHelper;
    private SupplierHelper $supplierHelper;
    protected configService $configService;

    public function __construct()
    {
        $this->categoryHelper = new CategoryHelper();
        $this->supplierHelper = new SupplierHelper();
        $this->configService = new configService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request): Response|RedirectResponse
    {

        //Obtener los datos del productos
        $data = $this->get($request);

        //Verificar si existe configuracion
        $setting = Setting::firstOrFail();

        //si existe la configuracion
        if(isset($setting))
        {
            //Devolver correctamente
            return Inertia::render('Products/Create',[
                'products' => $data,
                'categories' => $this->categoryHelper->getAllCategories(),
                'suppliers' => $this->supplierHelper->getAllSuppliers(),
            ]);

        }else{

            //Redirigir a la ventana de setting
            return to_route('setting.index');
        }




    }

    /**
     * Summary of store
     * @param StoreProductRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {

        $product = Product::create($request->validated());
        // Guardar los datos del productos
        if ($request->get('type') === 'servicio')
        {
            //Actualizar datos por fuera cuando son servicio
            $product->inventoried = false;
            $product->price = 1;
            $product->save();
        }


        // Devolver hacia atras
        return back();

    }

    /**
     * Summary of show
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        //validar los datos de busqueda
        $request->validate([
            'search' => ['nullable','max:50','string']
        ]);

        //Sacar los datos de busqueda
        $search = $request->get('search');

        // Realizar la busqueda
        $data = Product::where('status', true)
            ->where('name','LIKE', '%'. $search.'%')
            ->latest()
            ->simplePaginate();

        return Inertia::render('Products/Show',[
            'products' => $data
        ]);

    }

    /**
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function edit(Product $product, Request $request)
    {

        $dataProducts = $this->get($request);
        $dataEdit = new ProSupRes($product);

        return Inertia::render('Products/Create',[
            'productEdit' => $dataEdit,
            'products' => $dataProducts,
            'update' => true,
            'categories' => $this->categoryHelper->getAllCategories(),
            'suppliers' => $this->supplierHelper->getAllSuppliers(),
        ]);

    }

    /**
     * @param StoreProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(StoreProductRequest $request, Product $product)
    {

        // Actualizar los datos validados
        $product->update($request->validated());

        //Actualizar estos datos si es servicios
        if ($request->get('type') === 'servicio')
        {
            //Actualizar datos por fuera cuando son servicio
            $product->inventoried = false;
            $product->price = 1;
            $product->save();
        }

        // devolver hacia atras
        return back();
    }

    /**
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product)
    {

        //Actulizar los datos
        $product->status = false;
        $product->save();

        //Devolver atras
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getByCode(Request $request)
    {
        //conseguir los datos a buscar
        $search = $request->get('search');


        //Buscar los datos
        $data = Product::where('status', true)
            ->where(function ($query) use ($request, $search) {
                $query->where('code', $search)
                    ->orWhere('bar_code', $search);
            })->firstOrFail();


        //DEvolver los datos
        return response()->json($data);
    }


    // Para crear la entrada de producto
    public function in()
    {

        return Inertia::render('Products/In');

    }

    // Conseguir todo los productos
    /**
     * Summary of get
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function get(Request $request)
    {
        // Obntener los datos de busqueda
        $search = $request->get('search');

        // Realizar la busqueda
        return  Product::where('status', true)
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->simplePaginate(15);

    }


}

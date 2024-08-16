<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use App\Http\Requests\StoreProductInRequest;
use App\Http\Requests\UpdateProductInRequest;
use App\Models\ProTrans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ProductInController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {


//        Gate::authorize('create', Auth::user());

        //conseguir  los datos
        $data = $this->getProduct($request);

        //Devolver la vista con los datos
        return Inertia::render('ProductsIn/In',[
            'products' => $data
        ]);


    }

    /**
     * @return void
     */
    public function create()
    {

    }

    /**
     * @param StoreProductInRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductInRequest $request, Product $productIn)
    {

        DB::transaction(function () use ($request) {
            // Guardar los datos
            $product = Product::where('id', $request['product_id'])
                ->firstOrFail();

            //Actulizar los datos
            $product->stock += $request['stock'];
            $product->cost = $request['cost'];
            $product->price = $request['price'];
            $product->discount = $request['discount'];
            $product->save();

            //Crear la transaction del producto
            ProTrans::create([
                'product_id' => $request['product_id'],
                'stock' => $request['stock'],
                'sale_id' => 0,
                'cost' => $request['cost'],
                'price' => $request['price'],
                'tax' => $request['tax'],
                'amount' => $request['amount'],
                'type' => ProductTypeEnum::ENTRADA
            ]);
        });


        //Devolver hacia atras
        return back();

    }

    /**
     * Display the specified resource.
     */
    // public function show(ProductIn $productIn)
    // {
    //     //
    // }

    /**
     * @param Request $request
     * @param Product $productIn
     * @return Response
     */
    public function entrance(Request $request, Product $productIn)
    {

        // Tomar lo datos de todos los produtos
        $data = $this->getProduct($request);

        // Devolver la vista con los datos
        return Inertia::render('ProductsIn/In',[
            'products' => $data,
            'productEntrance' => $productIn,
        ]);

    }

    /**
     * @param UpdateProductInRequest $request
     * @param Product $productIn
     * @return string
     */
    public function update(UpdateProductInRequest $request, Product $productIn)
    {

        //Tomar las fechas
        $updateDay = config('Setting.document-update');

        // Formatear la fecha de creacion
        $created_at  = Carbon::parse($productIn->created_at);

        // tomar la fecha actual
        $now = Carbon::now();

        // si el parametro de actualiacion es mayor  a updated at
        if($created_at->greaterThan($now))
        {
            return 'No se puede actualizar el registro';
        }else{
            return 'Se ha actualizado correctamente';
        }

    }

    /**
     * @param Product $productIn
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $productIn)
    {

        //eliminar el producto
//        Gate::authorize('delete', Auth::user());

        //Actualziar los datos
        $productIn->status = true;
        $productIn->save();

        //Retornar hacia atras
        return back();

    }



    /**
     * @param Request $request
     * @return Paginator
     */
    private function getProduct(Request $request):Paginator
    {
        //Obtener los datos de busqueda
        $search = $request->get('search');

        //Devolver los datos
        return Product::where('status', false)
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->simplePaginate();

    }
}

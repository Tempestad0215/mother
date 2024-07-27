<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProSupRes;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Summary of create
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {

        $data = $this->get($request);



        return Inertia::render('Products/Create',[
            'products' => $data
        ]);

    }

    /**
     * Summary of store
     * @param \App\Http\Requests\StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {

        // Guardar los datos del productos
        Product::create($request->validated());

        // Devolver hacia atras
        return back();

    }

    /**
     * Summary of show
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {

        $request->validate([
            'search' => ['nullable','max:50','string']
        ]);

        $search = $request->get('search');

        $data = Product::where('status', false)
            ->where('name','LIKE', '%'. $search.'%')
            ->latest()
            ->simplePaginate();

        return Inertia::render('Products/Show',[
            'products' => $data
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     * @param Product $product
     * @param Request $request
     */
    public function edit(Product $product, Request $request)
    {

        $dataProducts = $this->get($request);
        $dataEdit = new ProSupRes($product);

        return Inertia::render('Products/Create',[
            'productEdit' => $dataEdit,
            'products' => $dataProducts,
            'update' => true
        ]);

    }

    /**
     * Update the specified resource in storage.
     * @param StoreProductRequest $request
     * @param Product $product
     */
    public function update(StoreProductRequest $request, Product $product)
    {

        // Actualizar los datos validados
        $product->update($request->validated());

        // devolver hacia atras
        return back();
    }

    /**
     * Summary of destroy
     * @param \App\Models\Product $product
     * @return void
     */
    public function destroy(Product $product)
    {
        //
    }


    // Para crear la entrada de producto
    public function in()
    {

        return Inertia::render('Products/In');

    }

    // Conseguir todo los productos
    /**
     * Summary of get
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function get(Request $request)
    {

        $search = $request->get('search');

        $data = Product::where('status', false)
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->simplePaginate(15);

        return $data;
    }
}

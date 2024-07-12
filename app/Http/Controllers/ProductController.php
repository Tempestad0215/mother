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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {

            return Inertia::render('Products/Create');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {

            // Guardar los datos del productos
            Product::create($request->validated());

            // Devolver hacia atras
            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
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

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try {

            $data = new ProSupRes($product);

            return Inertia::render('Products/Create',[
                'productEdit' => $data,
                'update' => true
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        try {

            // Actualizar los datos validados
            $product->update($request->validated());

            // devolver hacia atras
            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }


    //    Conseguir los productos
    public function get(Request $request)
    {
        try {

        // Conseguir los datos del searhc
        $search = $request->get('search');

        // conseguir los datos con el where
        $data = Product::where('status', false)
            ->where('name','LIKE', '%'. $search.'%')
            ->latest()
            ->limit(10)
            ->get();

        // Devolver los datos de la respuesta
        return response()->json($data);

        }catch (\Throwable $th) {
            return $th;
        }
    }

}

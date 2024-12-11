<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
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
    public function create(Request $request)
    {

        //Tomaar los datos de busqueda
        $data = $this->get($request);

        //Devolver la vista con los datos
        return Inertia::render("Suppliers/Create",[
            'suppliers' => $data
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
       // Guardar los datos de supplidor
        Supplier::create($request->validated());


        //Devolver hacia atras
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        //Tomaar los datos de busqueda
        $data = $this->get($request);

        //devolver la vista y los datos
        return Inertia::render("Suppliers/SupplierShow",[
           'suppliers' =>  $data
        ]);
    }

    /**
     * Show the form for editing the specified res ource.
     */
    public function edit(Supplier $supplier)
    {
        //Para edditar el suplidor
        return Inertia::render("Suppliers/Create",[
            'supplierEdit' => $supplier,
            'update' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {

        //Actualizar los datos del suplidor
        $supplier->update($request->validated());

        //Devolver hacia atras
        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {

        // Actualizar los datos
        $supplier->update([
            'deleted_at' => now()
        ]);

        //Devolver hacia atras
        return back();

    }


    // Obtener los suplidores por empreesa
    public function getJson(Request $request)
    {
        //Tomar los datos de busquead
        $search = $request->get('search');

        //tomar los datos limitado a 10
         $data = Supplier::search($search)
             ->where('status',true)
             ->take(10)
             ->get();

         //Devolver un json
        return response()->json($data);

    }

    /**
     * @param Request $request
     * @return Paginator
     */
    private function get(Request $request):Paginator
    {

        //Tomar los datos de busqueda
        $search = $request->get('search');

        //Devolver los datos paginado a 15
        return Supplier::search($search)
            ->where('status',true)
            ->latest('created_at')
            ->simplePaginate(15);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
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
        //Politica de borrado
        Gate::authorize('delete',Auth::user());

        // Actualizar los datos
        $supplier->update([
            'status' => true
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
         $data = Supplier::where('status',false)
             ->where('company_name', 'LIKE','%'.$search.'%')
             ->limit(10)
             ->get();

         //Devolver un json
        return response()->json($data);

    }

    private function get(Request $request)
    {

        //Tomar los datos de busqueda
        $search = $request->get('search');

        //Devolver los datos paginado a 15
        return Supplier::where('status',false)
            ->where(function($query) use ($search){
                $query->where('company_name', 'LIKE','%'.$search.'%')
                    ->orWhere('phone', 'LIKE','%'.$search.'%')
                    ->orWhere('email', 'LIKE','%'.$search.'%')
                    ->orWhere('contact', 'LIKE','%'.$search.'%');
            })->latest()
            ->paginate(15);

    }
}

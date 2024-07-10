<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
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
    public function create()
    {
        try {

            return Inertia::render("Suppliers/Create");

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        try {


            Supplier::create($request->validated());

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }


    // Obtener los suplidores por empreesa
    public function get(Request $request)
    {
        try {

            $request->validate([
                'search' => ['required','string','min:2','max:75']
            ]);

            $search = $request->get('search');

            // $data = Supplier::where('status',false)
            //     ->where('company_name', 'LIKE','%'.$search.'%')
            //     ->limit(10)
            //     ->get();

            $data = Supplier::orderBy('company_name','asc')
                ->get();

            return response()->json($data);



        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

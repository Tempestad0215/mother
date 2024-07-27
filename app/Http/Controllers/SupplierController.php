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
    public function create(Request $request)
    {
        try {

            $data = $this->get($request);


            return Inertia::render("Suppliers/Create",[
                'suppliers' => $data
            ]);

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
        try {

            $supplier->update($request->validated());

            return back();

        }catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {

            $supplier->update([
                'status' => true
            ]);

            return back();

        }catch (\Throwable $th) {
            throw $th;
        }
    }


    // Obtener los suplidores por empreesa
    public function getJson(Request $request)
    {
        try {

            $search = $request->get('search');

             $data = Supplier::where('status',false)
                 ->where('company_name', 'LIKE','%'.$search.'%')
                 ->limit(10)
                 ->get();


            return response()->json($data);



        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function get(Request $request)
    {
        $search = $request->get('search');

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

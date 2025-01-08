<?php

namespace App\Http\Controllers;

use App\Enums\ACTYEnum;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SUPController extends Controller
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
        return Inertia::render("Suppliers/SupplierCreate",[
            'suppliers' => $data
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {

        DB::transaction(function () use ($request) {
            // Guardar los datos de supplidor
            $supplier = Supplier::create($request->validated());

            //si existe el comentario pues se guarda los datos
            if ($request->has('comment'))
            {
                $supplier->comment()->create([
                    'content' => $request->comment,
                ]);
            }

            //si tiene otro tipo que no sea contado
            if ($request->get('type_payment') != 'contado')
            {
                $supplier->account()->create([
                    'type' => ACTYEnum::PAGAR,
                    'amount' => $request->get('amount'),
                    'due_date' => $request->get('due_date'),
                    'balance' => $request->get('amount'),
                    'late_fee' => $request->get('late_fee'),
                ]);
            }

        });

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
        return Inertia::render("Suppliers/SupplierCreate",[
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
        $supplier->delete();

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
     * @return mixed
     */
    private function get(Request $request):mixed
    {

        //Tomar los datos de busqueda
        $search = $request->get('search');

        //Devolver los datos paginado a 15
        $suppliers = Supplier::search($search)
            ->where('status',true)
            ->latest('created_at')
            ->simplePaginate(15);


        return SupplierResource::collection($suppliers)->response()->getData(true);

    }
}

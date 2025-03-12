<?php

namespace App\Http\Controllers;

use App\Enums\AccountTypeEnum;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SupplierController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth'),
            new Middleware('role:Super Admin|Supervisor'),
        ];
    }
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
        return Inertia::render("Suppliers/Register",[
            'suppliers' => $data
        ]);

    }

    /**
     * @param StoreSupplierRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSupplierRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Guardar los datos de supplidor
            $supplier = Supplier::create($request->validated());

            //si existe el comentario pues se guarda los datos
            if ($request->get('comment') != null )
            {
                $supplier->comment()->create([
                    'content' => $request->comment,
                ]);
            }

            //si tiene otro tipo que no sea contado
            if ($request->get('type_payment') != 'contado')
            {
                $supplier->account()->create([
                    'type' => AccountTypeEnum::PAGAR,
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
        return Inertia::render("Suppliers/Show",[
           'suppliers' =>  $data
        ]);
    }

    /**
     * Show the form for editing the specified res ource.
     */
    public function edit(Supplier $supplier)
    {
        //Para edditar el suplidor
        return Inertia::render("Suppliers/Register",[
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
        $search = trim($request->get('search'));
        $per_page = $request->get('per_page', 30);
        $field = $request->get('field','company_name');


        //Devolver los datos paginado a 15
        $suppliers = Supplier::query()
            ->where($field,'LIKE','%'.$search.'%')
            ->where('status',true)
            ->latest('created_at')
            ->simplePaginate($per_page);


        return SupplierResource::collection($suppliers)->response()->getData(true);

    }
}

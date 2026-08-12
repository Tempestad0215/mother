<?php

namespace App\Http\Controllers;

use App\Enums\AccountTypeEnum;
use App\Enums\PaymentTypeEnum;
use App\Exports\CategoryExport;
use App\Exports\SupplierExport;
use App\Http\Requests\PaginationRequest;
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
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;

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
     * Show the form for creating a new resource.
     */
    public function index(PaginationRequest $request)
    {

        //Tomar los datos de búsqueda
        $search = $request->search;
        $per_page = $request->per_page;
        $page = $request->page;

        $suppliers = Supplier::query()
            ->when($request->filled('search'), function ($query) use ($search) {
                $query->where('company_name', 'ILIKE', "%$search%")
                    ->orWhere('contact', 'ILIKE', "%$search%")
                    ->orWhere('email', 'ILIKE', "%$search%");
            })->orderBy('created_at', 'desc')
            ->simplePaginate($per_page)
            ->withQueryString();

        $paymentType = collect(PaymentTypeEnum::cases())->mapWithKeys(fn(paymentTypeEnum $value) => [$value->name => $value->value]);

        //Devolver la vista con los datos
        return Inertia::render("Suppliers/Register",[
            'suppliers' => SupplierResource::collection($suppliers),
            'paymentTypes' => $paymentType,
        ]);

    }

    /**
     * @param StoreSupplierRequest $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(StoreSupplierRequest $request)
    {

        DB::transaction(function () use ($request) {
            // Guardar los datos de supplier
            $supplier = Supplier::create($request->validated());

            //si tiene otro tipo que no sea contado
//            if ($request->get('type_payment') != 'Contado')
//            {
//                $supplier->account()->create([
//                    'type' => AccountTypeEnum::PAGAR,
//                    'amount' => $request->get('amount'),
//                    'due_date' => $request->get('due_date'),
//                    'balance' => $request->get('amount'),
//                    'late_fee' => $request->get('late_fee'),
//                ]);
//            }

        });

        //Devolver hacia atrás
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


    /**
     * @param Request $request
     * @return mixed
     */
    public static function get(Request $request):mixed
    {

        //Tomar los datos de busqueda
        $search = trim($request->input('search'));
        $per_page = $request->input('per_page', 30);


        //Devolver los datos paginado a 15
        $suppliers = Supplier::query()
            ->where('company_name','ilike','%'.$search.'%')
            ->orWhere('contact','ilike','%'.$search.'%')
            ->orWhere('email','ilike','%'.$search.'%')
            ->where('status',true)
            ->latest('created_at')
            ->simplePaginate($per_page);


        return SupplierResource::collection($suppliers)->response()->getData(true);

    }


    public static function getJson(Request $request)
    {
        //Tomar los datos de busqueda
        $search = trim($request->input('search', ''));


        //Devolver los datos paginado a 15
        $suppliers = Supplier::query()
            ->where('company_name','ilike','%'.$search.'%')
            ->orWhere('contact','ilike','%'.$search.'%')
            ->orWhere('email','ilike','%'.$search.'%')
            ->where('status',true)
            ->latest('created_at')
            ->take(15);

        return response()->json($suppliers->get());
    }



    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel()
    {

        return Excel::download(new SupplierExport, 'categorias.xlsx');
    }
}

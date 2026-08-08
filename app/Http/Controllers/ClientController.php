<?php

namespace App\Http\Controllers;

use App\Enums\ClientDocumentEnum;
use App\Enums\ClientTypeEnum;
use App\Enums\ClientTypePriceEnum;
use App\Helpers\ClientHelper;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use App\Http\Resources\ClientCommentResource;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ClientController extends Controller implements HasMiddleware
{
    public ClientHelper $clientHelper;


    /**
     * Para los middleware del controllador
     * @return array
     */
    public static function middleware()
    {
        return [
            new Middleware('auth'),
            new Middleware('role:Super Admin|Supervisor',),
        ];
    }


    /**
     *
     */
    public function __construct()
    {
        $this->clientHelper = new ClientHelper();
    }

    /**
     * @return Response
     */
    public function index(Request $request)
    {
        // Validar los datos
        $request->validate([
            'search' => ['nullable','string'],
        ]);

        // Tomar los datos
        $data = $this->getTable($request);

        // Convertir los enums de los datos
        $clientType = ClientTypeEnum::options();
        $clientPrice = ClientTypePriceEnum::options();
        $clientDocument = ClientDocumentEnum::options();

        /*Vista con la pagina*/
        return Inertia::render('Clients/RegisterClient',[
            'typeRNC' => config('appconfig.sequenceSale'),
            'clientData' => $data,
            'clientType' => $clientType,
            'clientPrice' => $clientPrice,
            'clientDocument' => $clientDocument,
        ]);

    }

    /**
     * @param StoreClientsRequest $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws Throwable
     */
    public function store(StoreClientsRequest $request)
    {
        //Guardar los datos
        $this->clientHelper->store($request);


        // Devolver hacia atrás
        return Inertia::flash("message", "Datos Registrado conExisto")->back();

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        // Validar los datos
        $request->validate([
            'search' => ['nullable','string']
        ]);

        // Tomar los datos
        $data = $this->getTable($request);


        //Devolver los datos
        return Inertia::render('Clients/ShowClient',[
            'clients' => $data
        ]);

    }

    /**
     * @param Client $client
     * @return Response
     */
    public function edit(Client $client)
    {

        // Devolver la vista con los datos
        return Inertia::render('Clients/RegisterClient',[
            'update' => true,
            'clientEdit' => new ClientCommentResource($client),
            'typeRNC' => config('appconfig.sequenceSale')
        ]);

    }

    /**
     * @param UpdateClientsRequest $request
     * @param Client $client
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdateClientsRequest $request, Client $client)
    {

        //Actualizer los datos
        $this->clientHelper->update($request, $client);
        // Devolver hacia atras
        return back();

    }

    /***
     * @param Client $client
     * @return RedirectResponse
     */
    public function destroy(Client $client)
    {

        //Verificar si el usuario tiene permiso
//        Gate::authorize('destroy', Auth::user());

        // Actualizar los datos
        $client->delete();

        // Retornar hacia atras
        return back();

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getJson(Request $request)
    {
        //Obtener los datos para buscar
        $search = $request->input('search');

        //Buscar los datos
        $data = Client::query()
            ->where('status', true)
            ->when($request->filled('search'), function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->orWhere('name', 'ILIKE', '%' . $search . '%')
                        ->orWhere('phone', 'ILIKE', '%' . $search . '%');
                });
            })
            ->latest('created_at')
            ->limit(15)
            ->get();

        //Devolver los datos en json
        return response()->json($data);

    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    private function getTable(Request $request)
    {
        // Tomar los datos
        $search = trim($request->input('search'));
        $perPage = $request->input('perPage',30);

        $clients = Client::query()
            ->where('status',true)
            ->when($request->filled('search'), function ($query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('name','ILIKE','%'.$search.'%')
                        ->orWhere('email','ILIKE','%'.$search.'%')
                        ->orWhere('code','ILIKE','%'.$search.'%');
                });

            })->paginate($perPage)
            ->withQueryString();

        // Buscar en la base de datos
        return ClientCommentResource::collection($clients);

    }


//    /**
//     * @throws Exception
//     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
//     */
//    public function exportExcel()
//    {
//
//        return Excel::download(new ClientExport, 'clientes.xlsx');
//    }



}

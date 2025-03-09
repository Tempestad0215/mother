<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Http\Resources\ClientCommentResource;
use App\Models\Client;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

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
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * @return Response
     */
    public function create(Request $request)
    {
        // Validar los datos
        $request->validate([
            'search' => ['nullable','string'],
        ]);

        // Tomar los datos
        $data = $this->getTable($request);

        /*Vista con la pagina*/
        return Inertia::render('Clients/Register',[
            'clients' => $data,
        ]);

    }

    /**
     * @param StoreClientsRequest $request
     * @return RedirectResponse
     */
    public function store(StoreClientsRequest $request)
    {

        //Guardar los datos
        $this->clientHelper->store($request);

        // Devolver hacia atras
        return back();

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
        return Inertia::render('Clients/Show',[
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
        return Inertia::render('Clients/ClientCreate',[
            'update' => true,
            'clientEdit' => new ClientCommentResource($client) ,
        ]);

    }

    /**
     * @param UpdateClientsRequest $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function update(UpdateClientsRequest $request, Client $client)
    {
        //Actualizar los datos
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
        $search = $request->get('search');

        //Buscar los datos
        $data = Client::where('status',false)
            ->where(function ($query) use ($search) {
                $query->where('name','like','%'. $search .'%')
                    ->orWhere('phone','like','%'. $search .'%');
            })
            ->latest('created_at')
            ->limit(5)
            ->get();

        //Devolver los datos en json
        return response()->json($data);

    }

    /**
     * @param Request $request
     * @return Paginator
     */
    private function getTable(Request $request)
    {
        // Tomar los datos
        $search = trim($request->get('search'));
        $perPage = $request->get('perPage',30);


        // Buscar en la base de datos
        return Client::search($search)
            ->where('status',true)
            ->latest('created_at')
            ->simplePaginate($perPage);

    }



}

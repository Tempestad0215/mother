<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Http\Resources\ClientCommentResource;
use App\Models\Client;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use LaravelIdea\Helper\App\Models\_IH_Client_C;

class ClientController extends Controller
{
    public ClientHelper $clientHelper;

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

        return Inertia::render('Clients/Create',[
            'clients' => $data,
            'test' => config('Setting.cliCode')
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
        return Inertia::render('Clients/Create',[
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

        // Actualizar todos los datos
        $client->update($request->validated());

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
        Gate::authorize('destroy', Auth::user());


        // Actualizar los datos
        $client->status = true;
        $client->save();




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
            ->latest()
            ->limit(5)
            ->get();

        //Devolver los datos en json
        return response()->json($data);

    }

    /**
     * @param Request $request
     * @return Client[]|Paginator|_IH_Client_C
     */
    private function getTable(REquest $request)
    {
        // Tomar los datos
        $search = $request->get('search');

        // Buscar en la base de datos
        return Client::where('status',true)
            ->where(function ($query) use ($search) {
                $query->where('name','like','%'. $search .'%')
                    ->orWhere('email','like','%'. $search .'%')
                    ->orWhere('phone','like','%'. $search .'%');
            })
            ->latest()
            ->simplePaginate();

    }



}

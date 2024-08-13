<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use http\Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ClientsController extends Controller
{
    /**
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        // Validar los datos
        $request->validate([
            'search' => ['nullable','string']
        ]);

        // Tomar los datos
        $data = $this->getTable($request);

        return Inertia::render('Clients/Create',[
            'clients' => $data
        ]);

    }

    /**
     * @param StoreClientsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreClientsRequest $request)
    {

        // Introducir los datos
        Clients::create($request->validated());

        // Devolver hacia atras
        return back();

    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        // Validar los datos
        $request->validate([
            'search' => ['nullable','string']
        ]);

        // Tomar los datos
        $data = $this->getTable($request);

        return Inertia::render('Clients/Show',[
            'clients' => $data
        ]);

    }

    /**
     * @param Clients $client
     * @return \Inertia\Response
     */
    public function edit(Clients $client)
    {
        // Devolver la vista con los datos
        return Inertia::render('Clients/Create',[
            'update' => true,
            'clientEdit' => $client
        ]);

    }

    /**
     * @param UpdateClientsRequest $request
     * @param Clients $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateClientsRequest $request, Clients $client)
    {

        // Actualizar todos los datos
        $client->update($request->validated());

        // Devolver hacia atras
        return back();

    }

    /***
     * @param Clients $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Clients $client)
    {

        $response = Gate::inspect('destroy', $client);

        if($response->allowed()){
            // Actualizar los datos
            $client->status = true;
            $client->save();
        }else{
            throw new AuthorizationException($response->message());
        }




        // Retornar hacia atras
        return back();

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJson(Request $request)
    {
        //Obtener los datos para buscar
        $search = $request->get('search');

        //Buscar los datos de todo
        $data = Clients::where('status',false)
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
     * @return mixed
     */
    private function getTable(REquest $request)
    {
        // Tomar los datos
        $search = $request->get('search');

        // Buscar en la base de datos
        return Clients::where('status',false)
            ->where(function ($query) use ($search) {
                $query->where('name','like','%'. $search .'%')
                    ->orWhere('email','like','%'. $search .'%')
                    ->orWhere('phone','like','%'. $search .'%');
            })
            ->latest()
            ->simplePaginate();

    }



}

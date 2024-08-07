<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use http\Client;
use Illuminate\Http\Request;
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
    public function create()
    {

        return Inertia::render('Clients/Create');

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
        $search = $request->get('search');

        // Buscar en la base de datos
        $data = Clients::where('status',false)
            ->where(function ($query) use ($search) {
                $query->where('name','like','%'. $search .'%')
                ->orWhere('email','like','%'. $search .'%')
                ->orWhere('phone','like','%'. $search .'%');
            })
            ->latest()
            ->simplePaginate();


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

        // Actualizar los datos
        $client->status = true;
        $client->save();

        // Retornar hacia atras
        return back();

    }


    public function getJson(Request $request)
    {
        //Obtener los datos para buscar
        $search = $request->get('search');

        //Buscar los datos de todo
        $data = Client::where('status',false)
            ->where(function ($query) use ($search) {
                $query->where('name','like','%'. $search .'%')
                    ->orWhere('phone','like','%'. $search .'%');
            })
            ->latest()
            ->limit(10)
            ->get();

        //Devolver los datos en json
        return response()->json($data);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsController extends Controller
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

            return Inertia::render('Clients/Create');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientsRequest $request)
    {
        try {

            // Introducir los datos
            Clients::create($request->validated());

            // Devolver hacia atras
            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
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

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clients $client)
    {
        try {
            // Devolver la vista con los datos
            return Inertia::render('Clients/Create',[
                'update' => true,
                'clientEdit' => $client
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientsRequest $request, Clients $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clients $client)
    {
        try {

            // Actualizar los datos
            $client->status = true;
            $client->save();

            // Retornar hacia atras
            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

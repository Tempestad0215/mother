<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WHController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {

        //Devolver la vista con los datos
        return Inertia::render('Setting/WH/WHIndex',[
            'warehouse' => Warehouse::all()
        ]);
    }

    public function store(Request $request)
    {
        //Validar los datos
        $request->validate([
            'name' => ['required', 'string', 'max:75'],
            'description' => ['required', 'string', 'max:200'],
        ]);


        // Craer los datos
        Warehouse::create($request->toArray());

    }

    public function show(Warehouse $warehouse)
    {
        $this->authorize('view', $warehouse);

        return $warehouse;
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);

        $warehouse->update($request->validated());

        return $warehouse;
    }

    public function destroy(Warehouse $warehouse)
    {
        $this->authorize('delete', $warehouse);

        $warehouse->delete();

        return response()->json();
    }
}

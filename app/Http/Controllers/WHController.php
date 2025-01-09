<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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

    public function store(WarehouseRequest $request)
    {
        $this->authorize('create', Warehouse::class);

        return Warehouse::create($request->validated());
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

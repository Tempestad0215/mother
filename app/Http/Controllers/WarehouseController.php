<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller
{
    use AuthorizesRequests;

    /**
     * @return Response
     */
    public function index()
    {

        //Devolver la vista con los datos
        return Inertia::render('Setting/WH/WHIndex',[
            'warehouse' => Warehouse::all()
        ]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        //Validar los datos
        $request->validate([
            'name' => ['required', 'string', 'max:75'],
            'description' => ['required', 'string', 'max:200'],
            'location' => ['required', 'string', 'max:200'],
        ]);

        // Craer los datos
        Warehouse::create($request->toArray());

        // Devolver
        return back();

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


    /**
     * @param Warehouse $wh
     * @return RedirectResponse
     */
    public function destroy(Warehouse $wh)
    {

        // Eliminar
        $wh->delete();

        // DEvolver hacia atras
        return back();
    }
}

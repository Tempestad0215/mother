<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;


    /**
     * Para los middleware del controllador
     * @return array
     */
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('role:Super Admin|Supervisor',),
        ];
    }


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
            'location' => ['nullable', 'string', 'max:200']
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



    /**
     * Actualizar los datos
     */
    public function update(WarehouseRequest $request, Warehouse $wh)
    {
        //Validar los datos
        $request->validate([
            'name' => ['required', 'string', 'max:75'],
            'description' => ['required', 'string', 'max:200'],
            'location' => ['nullable', 'string', 'max:200'],
        ]);

        // Actualizar los datos
        $wh->update($request->toArray());

        //Devolver la vista
        return back();
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

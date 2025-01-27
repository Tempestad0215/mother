<?php

namespace App\Http\Controllers;

use App\Helpers\SettingHelper;
use App\Http\Requests\StoreSettingRequest;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller implements HasMiddleware
{
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
        return Inertia::render('Setting/SettingIndex',[
            'setting' => Setting::first(),
            'company_type' => config('appconfig.company_type'),
        ]);
    }


    /**
     * @param StoreSettingRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSettingRequest $request)
    {
        //Verificar si existe venta con la cuenta abierta
        $sale = Sale::where('close_table', false)->exists();

        //Verificar si existe para devolver el mensaje
        if ($sale && $request->get('sequence'))
        {
            //Enviar mensaje de error, hasta cerrar las cuentas no se puede cambiar la sequencia
            throw ValidationException::withMessages([
                'general' => 'Por Favor, Antes De Cambiar El Modo (Manejar Comprobante) Debes Cerrar Todas Las Cuentas.'
            ]);
        }

        //Crear la instancia de los datos
        $settingHelper = new SettingHelper();

        //Llmar los datos
        $settingHelper->store($request);

        //Devolver hacia atras
        return back();

    }
}

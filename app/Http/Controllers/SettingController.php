<?php

namespace App\Http\Controllers;

use App\Helpers\SettingHelper;
use App\Http\Requests\StoreSettingRequest;
use App\Models\Setting;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Setting/Index',[
            'setting' => Setting::first()
        ]);
    }


    /**
     * @param StoreSettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSettingRequest $request)
    {
        //Crear la instancia de los datos
        $settingHelper = new SettingHelper();

        //Llmar los datos
        $settingHelper->store($request);

        //Devolver hacia atras
        return back();

    }

    public function getJson()
    {
        //Buscar los datos de la configuracion
        $data = Setting::first();

        //Devolver los datos
        return response()->json($data);
    }




}

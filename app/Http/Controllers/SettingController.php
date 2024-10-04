<?php

namespace App\Http\Controllers;

use App\Helpers\SettingHelper;
use App\Http\Requests\StoreSettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Setting/Index',[
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
        //Crear la instancia de los datos
        $settingHelper = new SettingHelper();

        //Llmar los datos
        $settingHelper->store($request);

        //Devolver hacia atras
        return back();

    }


//    /**
//     * @return JsonResponse
//     */
//    public function getJson()
//    {
//        //Buscar los datos de la configuracion
//        $data = Setting::first();
//
//        //Devolver los datos
//        return response()->json($data);
//    }




}

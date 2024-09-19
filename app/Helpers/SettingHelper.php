<?php

namespace App\Helpers;

use App\Http\Requests\StoreSettingRequest;
use App\Models\Setting;;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;




class SettingHelper
{

    public function store(StoreSettingRequest $request)
    {

        DB::transaction(function () use ($request) {
            //Guradar la imagen y devolver el nombre
            $imageName = $this->savePhoto($request->file('logo'));

            //Verificar si existe
            $exists = Setting::first();

            //Si existe actualziar los datos
            if(isset($exists))
            {
                $exists->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'website' => $request->website,
                    'company_id' => $request->company_id,
                    'tax' => $request->tax,
                    'unit' => $request->unit,
                    'fiscal_year' => $request->fiscal_year,
                    'logo' => $imageName,
                    'save_cost' => $request->save_cost
                ]);
            }else{
                //actualizar los datos
                Setting::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'website' => $request->website,
                    'company_id' => $request->company_id,
                    'tax' => $request->tax,
                    'unit' => $request->unit,
                    'fiscal_year' => $request->fiscal_year,
                    'logo' => $imageName,
                    'save_cost' => $request->save_cost
                ]);
            }

        });

    }




    public function savePhoto(?UploadedFile $file)
    {
        //Obtener la foto guardada por el id
        $fileName = Setting::first()->logo ?? "";

        //Verificar si existe la imagen para eliminar
        if(Storage::disk('images')->exists($fileName))
        {
            //Eliminar la imagen del disco
            Storage::disk('images')->delete($fileName);
        }

        //Verificar si existe el archivo
        if(isset($file))
        {
            //Crear el nombre hash
            $hashName = $file->hashName();
            Storage::putFileAs('public/images', $file, $hashName);

        }else
        {
            //Poner el nombre igual
            $hashName = $fileName;
        }


        //Devolver el nombre de la imagen
        return $hashName;
    }
}

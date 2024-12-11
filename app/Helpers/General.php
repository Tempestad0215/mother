<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class General
{


    /**
     * @param Request $request
     * @param Model $model
     * @return string|null
     */
    public function saveImage(Request $request, Model $model): string|null
    {


        // Verificar si el request tiene la imagen
        if ($request->hasFile('image')) {

            return DB::transaction(function () use ($request, $model) {
                // Sacar El nombre de la imagen
                $name = $request->file('image')->hashName();

                //Imagen antigua
                $oldImage = $model->image?->name;

                // Eliminar la imagen si existe
                if ($oldImage && Storage::disk('images')->exists($oldImage)) {
                    Storage::disk('images')->delete($oldImage);
                }

                // Guadar la imagen
                $request->file('image')->storeAs('images', $name);

                //si la imagen existe, quiere decir que ya se creo una relacion
                if ($oldImage)
                {
                    $model->image()->update(['name' => $name]);
                }else{
                    $model->image()->create(['name' => $name]);
                }

                return true;
            });

        }

        //Devolver nulo
        return false;

    }

}

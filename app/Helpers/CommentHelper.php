<?php

namespace App\Helpers;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class CommentHelper
{
    /**
     * @param Model $model
     * @param string $content
     * @return void
     */
    public function updateOrInsert(Model $model, string $content):void
    {

        //Sarcar el id del modelo
        $id = $model->id;
        //Obtener la clase
        $getClass = get_class($model);

        //Buscar la coincidencia
        $existsModel = Comment::where('commentable_id', $id)->where('commentable_type', $getClass)->first();

        //si existe solo se actualizan los datos
        if($existsModel){

            //Actualizar los datos
            $existsModel->content = $content;
            $existsModel->save();

            //Si no existe solo se va instroducir
        }else{

            //Verificar si existen los datos
            if($content !== "")
            {
                $model->comment()->create([
                    'content' => $content,
                ]);
            }
        }


    }
}

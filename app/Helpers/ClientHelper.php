<?php

namespace App\Helpers;

use App\Http\Requests\StoreClientsRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientHelper
{
    /**
     * Devolver los datos del cliente
     *
     * @param Request $request
     * @return mixed
     */
    public  function  get(Request $request):mixed
    {

        //conseguir los datos del cliente
        return Client::where('status', true)
            ->where('name','LIKE','%'.$request->get('search').'%' )
            ->latest()
            ->simplePaginate(15);

    }

    /**
     * @param StoreClientsRequest $request
     * @return void
     */
    public function store(StoreClientsRequest $request):void
    {

        //Asegurar la transaccion de la introducion de datos
        DB::transaction(function () use ($request) {


            //Obtener el tipo
            $type = (int) $request->get('type');


            //Guardar los datos validado
           $client = Client::create($request->validated());

           //Tomar el nombre del comentario
           $commentHelper = new CommentHelper();
           $commentHelper->updateOrInsert($client, $request->get('comment'));

           //si es avance
           if($type === 3)
           {
               //Crear la instancia
               $advanceHelper = new AdvanceHelper();

               //Guardar los datos
               $advanceHelper->store($request, $client->id);

               //Si es credito
           }else if($type === 2){

               //Crear la instancia
               $creditHelper = new CreditHelper();
               //Enviar los datos
               $creditHelper->store($request, $client->id);
           }


        });
    }
}

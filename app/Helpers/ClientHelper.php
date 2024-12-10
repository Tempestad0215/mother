<?php

namespace App\Helpers;

use App\Http\Requests\StoreClientsRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

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

        $search = $request->get('search');
        $perPage = $request->get('perPage',15);

        //conseguir los datos del cliente
        return Client::where('status', true)
            ->where('name','LIKE','%'.$search.'%' )
            ->latest()
            ->simplePaginate($perPage);

    }

    /**
     * @param StoreClientsRequest $request
     * @return void
     */
    public function store(StoreClientsRequest $request):void
    {

        //Asegurar la transaccion de la introducion de datos
        DB::transaction(function () use ($request) {


            //Inmtancia
            $general = new General();

            //Obtener el tipo
            $type = (int) $request->get('type');


            //Guardar los datos validado
           $client = Client::create($request->validated());


           //guardar el comentario si existe
           if ($request->get('comment') !== null)
           {
               //Tomar el nombre del comentario
               $commentHelper = new CommentHelper();
               $commentHelper->updateOrInsert($client, $request->get('comment'));
           }

           // Obtner el nombre de la imagen ya guardado
           $oldImage = $client->image?->name;

           //Guardar la imagen y quedarse con el nombre
           $general->saveImage($request, $oldImage, $client);


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

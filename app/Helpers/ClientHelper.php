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
            ->latest('created_at')
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
            $type = $request->get('type');


            //Guardar los datos validado
           $client = Client::create($request->only([
               'name',
               'email',
               'personal_id',
               'phone',
               'address',
               'type',
               'document',
               'receive_email',
               'status',
               'type_price']));


           //guardar el comentario si existe
           if ($request->get('comment') !== null)
           {
               //Tomar el nombre del comentario
               $commentHelper = new CommentHelper();
               $commentHelper->updateOrInsert(
                   $client,
                   $request->get('comment'));
           }

           //Guardar la imagen y quedarse con el nombre
           $general->saveImage($request, $client);

           if ($type != 'contado')
           {
               $client->credit()->create([
                   'limit' => $request->get('limit'),
                   'due_date' => $request->get('due_date'),
                   'balance' => $request->get('limit'),
                   'consumed' =>  0,
                   'late_fee_interest' => $request->get('late_fee_interest'),
               ]);

           }


//           //si es avance
//           if($type === 3)
//           {
//               //Crear la instancia
//               $advanceHelper = new AdvanceHelper();
//
//               //Guardar los datos
//               $advanceHelper->store($request, $client->id);
//
//               //Si es credito
//           }else if($type === 2){
//
//               //Crear la instancia
//               $creditHelper = new CreditHelper();
//               //Enviar los datos
//               $creditHelper->store($request, $client->id);
//           }


        });
    }
}

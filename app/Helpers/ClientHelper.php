<?php

namespace App\Helpers;

use App\Enums\AccountTypeEnum;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
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
        $field = $request->get('field','name');


        $fieldAllowed = ['name','document','phone','personal_id','email'];

        //conseguir los datos del cliente
        return Client::where('status', true)
            ->where($field,'LIKE','%'.$search.'%' )
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
           $client = Client::create($request->validated());


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
               $client->account()->create([
                   'type' => AccountTypeEnum::COBRAR,
                   'amount' => $request->get('amount'),
                   'due_date' => $request->get('due_date'),
                   'balance' => $request->get('amount'),
                   'late_fee' => $request->get('late_fee'),
               ]);

           }

        });
    }

    /**
     * @param UpdateClientsRequest $request
     * @param Client $client
     * @return void
     */
    public function update(UpdateClientsRequest $request, Client $client):void
    {
        //Asegurar que se cumpla la transaccion
        DB::transaction(function () use ($request, $client) {
            //Obtener el tipo
            $type = $request->get('type');

            //Actualizar el cliente
            $client->update($request->only([
                'name',
                'email',
                'personal_id',
                'phone',
                'address',
                'type',
                'document',
                'receive_email',
                'status',
                'type_price'
            ]));
            //Verificar si llega el comentario
            if ($request->get('comment') !== null)
            {
                //Tomar el nombre del comentario
                $commentHelper = new CommentHelper();
                $commentHelper->updateOrInsert(
                    $client,
                    $request->get('comment'));
            }
            //Verificar el tipo de pago
            if ($type != 'contado')
            {
                $client->account()->updateOrInsert(
                    ['accountable_id' => $client->uuid],
                    [
                    'type' => AccountTypeEnum::COBRAR,
                    'amount' => $request->get('amount'),
                    'due_date' => $request->get('due_date'),
                    'balance' => $request->get('amount'),
                    'late_fee' => $request->get('late_fee'),
                ]);

            }
        });

    }
}

<?php

namespace App\Helpers;

use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientHelper
{
    /**
     * Devolver los datos del cliente
     *
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request): mixed
    {

        $search = $request->input('search');
        $perPage = $request->input('perPage', 15);


        $fieldAllowed = ['name', 'document', 'phone', 'personal_id', 'email'];
        $field = in_array($request->input('field'), $fieldAllowed, true)
            ? $request->input('field')
            : 'name'; // fallback seguro

        //conseguir los datos del cliente
        return Client::where($field, 'LIKE', '%' . $search . '%')
            ->latest('created_at')
            ->simplePaginate($perPage);

    }


    /**
     * @param StoreClientsRequest $request
     * @return void
     * @throws Throwable
     */
    public function store(StoreClientsRequest $request): void
    {

        //Asegurar la transacción de la introducción de datos
        DB::transaction(function () use ($request) {

            //Instancia
            $general = new General();
            //Obtener el tipo
            //Guardar los datos validados
            $client = Client::create($request->validated());

            //Guardar la imagen y quedarse con el nombre
            $general->saveImage($request, $client);

//           if ($type != 'contado')
//           {
//               $client->account()->create([
//                   'type' => AccountTypeEnum::COBRAR,
//                   'amount' => $request->input('amount'),
//                   'due_date' => $request->input('due_date'),
//                   'balance' => $request->get('amount'),
//                   'late_fee' => $request->get('late_fee'),
//               ]);
//
//           }

        });
    }

    /**
     * @param UpdateClientsRequest $request
     * @param Client $client
     * @return void
     * @throws Throwable
     */
    public function update(UpdateClientsRequest $request, Client $client): void
    {
        //Asegurar que se cumpla la transaccion
        DB::transaction(function () use ($request, $client) {
            //Obtener el tipo

            //Actualizar el cliente
            $client->update($request->validated());

            //Verificar el tipo de pago
//            if ($type != 'contado') {
//                $client->account()->updateOrInsert(
//                    ['accountable_id' => $client->uuid],
//                    [
//                        'type' => AccountTypeEnum::COBRAR,
//                        'amount' => $request->get('amount'),
//                        'due_date' => $request->get('due_date'),
//                        'balance' => $request->get('amount'),
//                        'late_fee' => $request->get('late_fee'),
//                    ]);
//
//            }
        });

    }
}

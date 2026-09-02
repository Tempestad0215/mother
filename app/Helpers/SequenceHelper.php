<?php

namespace App\Helpers;

use App\Enums\SaleTypeEnum;
use App\Enums\SequenceSaleTypeEnum;
use App\Http\Requests\SequenceRequest;
use App\Models\Sequence;
use App\Models\Setting;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use SplFileObject;
use Throwable;

class SequenceHelper
{

    /**
     * Guardar las secuencias y actualizar
     * @param SequenceRequest $request
     * @return RedirectResponse
     */
    public function store(SequenceRequest $request):RedirectResponse
    {
        //Buscar registro existente
        $exits = Sequence::where('type', $request->input('type'))->latest()->first();

        //Obtner el from nuevo
        $from =  (int)$request->input('from');

        if ($exits && $from <= $exits->to) {

            //DEvolver el mensaje con el error
            return back()->withErrors([
                'from' => "Por favor, la nueva secuancia no es valida. El ultimo registro fue $exits->to, este debe ser Mayor a $exits->to",
            ]);

        }else{
            //Si existe se acutaliza, en caso contrario se vas a crear
            Sequence::updateOrCreate(
                ['uuid' => $request->input('uuid')],
                [
                    'type' => $request->input('type'),
                    'from' => $request->input('from'),
                    'to' => $request->input('to'),
                    'next' => $request->input('id') != 0 ? $request->input('next') : $request->input('from'),
                    'advise' => $request->input('advise'),
                    'num_authorization' => $request->input('num_authorization'),
                    'num_request' => $request->input('num_request'),
                    'date_request' => $request->input('date_request'),
                    'date_expire' => $request->input('date_expire'),
                ]);

            //devolver hacia atras
            return back();
        }
    }

    /**
     * Obtener los RNCS
     * @return Collection
     */
    public function getAll():Collection
    {
        //Retornar todas las secuencias registrada
        return Sequence::orderBy('type')->get();
    }


    /**
     * Conseguir el rnc
     * @param SequenceSaleTypeEnum $type
     * @return JsonResponse
     * @throws Throwable
     */
    public function get(SequenceSaleTypeEnum $type):JsonResponse
    {
        //retonar el primer elemento solicitado
        $sequence = Sequence::where('type', $type)
            ->where('status', true)
            ->lockForUpdate()
            ->first();

       //Verificar si la secuancia existe
        if (!$sequence) {
            DB::rollBack();
            return response()->json([
                'error' => 'El tipo de Secuancia no existe en lo registro.'
            ],404);
        }


        //Devolver el mensaje de existo
        return response()->json($sequence);
    }


    /**
     * @param string $rnc
     * @return JsonResponse
     */
    public function getRnc(string $rnc):JsonResponse
    {
        //Para guardar la coincidencia
        $matches = "";

        //Obtener la ruta del arhivo
        $path = public_path("storage/rncs/DGII_RNC.txt");

        //Abrir el archivo
        $file = new SplFileObject($path, 'r');

        //Recorre el archivo linea a linea
        while (!$file->eof()) {
            $line = $file->fgets();

            //Buscar la linea que busco
            if(stripos($line, $rnc) !== false){
                //Pasar la coincidencia a la variable
                $matches = $line;
            }

        }

        //Limpiar los datos
        $cleanMatches = explode("|", $matches);

        //limpiar el nombre y solo dejar el espacio convensional
        $cleanName = preg_replace('/\s+/', ' ', $cleanMatches[1]);

        //Retornar los datos necesarios
        $data = [
            'rnc' => $cleanMatches[0],
            'razon_social' => trim($cleanName),
            'status' => $cleanMatches[9],
            'type' => str_replace("\r\n", "", $cleanMatches[10]),
        ];

        //Devolver losd atos
        return response()->json($data);

    }

    /**
     * @param SequenceSaleTypeEnum $type
     * @param SaleTypeEnum $saleType
     * @return void
     * @throws Throwable
     */
    public static function incrementSequence(SequenceSaleTypeEnum $type, SaleTypeEnum $saleType):void
    {

        if($saleType === SaleTypeEnum::COTIZACION)
        {
            return;
        }

        //Obtenr la configuracion
        $setting = Setting::getGlobal();

        if(!$setting?->sequence){
            return;
        }

        DB::transaction(function () use ($type, $saleType) {
           $sequence = Sequence::where('type', $type)
               ->where('status', true)
               ->lockForUpdate()
               ->first();

            // 4. Protección contra nulos
            if (!$sequence) {
                throw new Exception("No hay una secuencia activa disponible para el tipo: $type->value");
            }

            // 5. Incrementar
            $sequence->increment('next');

            // 6. Verificar si alcanzó o superó el límite
            if ($sequence->next >= $sequence->to) {
                $sequence->update([
                    'status' => false // Usar booleano directo
                ]);
            }

        });
    }

}

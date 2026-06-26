<?php

namespace App\Helpers;

use App\Enums\SequenceSaleTypeEnum;
use App\Http\Requests\SequenceRequest;
use App\Models\Sequence;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use SplFileObject;

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
     */
    public function get(SequenceSaleTypeEnum $type):JsonResponse
    {
        //retonar el primer elemento solicitado
        $sequence = Sequence::where('type', $type)
            ->where('status', true)
            ->first();

       //Verificar si la secuancia existe
        if (!$sequence) {
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
     * @param Request $request
     * @return void
     */
    public static function incrementSequence(SequenceSaleTypeEnum $type, Request $request):void
    {

        //Obtenr la configuracion
        $setting = Setting::getGlobal();

        //Verificar si la sercuencia existe
        if($setting->sequence)
        {
            //Obtener el primer registro del tipo seleccionado
            $sequence = Sequence::where('type', $type)
                ->where('status', true)
                ->first();

            //Incrementar la secuencia a 10
            $sequence->increment('next');

            //Verificar si ya llego al final
            if ($sequence->to == $sequence->next)
            {
                //Desetimar este sequencia
                $sequence->update([
                    'status' => 0
                ]);
            }
        }
    }

}

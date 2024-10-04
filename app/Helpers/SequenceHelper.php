<?php

namespace App\Helpers;

use App\Enums\SequenceTypeEnum;
use App\Http\Requests\SequenceRequest;
use App\Models\Sequence;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
        $exits = Sequence::where('type', $request->get('type'))->latest()->first();



        //Obtner el from nuevo
        $from =  (int)$request->get('from');


        if ($exits && $from <= $exits->to) {

            //DEvolver el mensaje con el error
            return back()->withErrors([
                'from' => "Por favor, la nueva secuancia no es valida. El ultimo registro fue $exits->to, este debe ser Mayor a $exits->to",
            ]);

        }else{
            //Si existe se acutaliza, en caso contrario se vas a crear
            Sequence::updateOrCreate(
                ['id' => $request->get('id')],
                [
                    'type' => $request->get('type'),
                    'from' => $request->get('from'),
                    'to' => $request->get('to'),
                    'next' => $request->get('id') != 0 ? $request->get('next') : $request->get('from'),
                    'advise' => $request->get('advise'),
                    'num_authorization' => $request->get('num_authorization'),
                    'num_request' => $request->get('num_request'),
                    'date_request' => $request->get('date_request'),
                    'date_expire' => $request->get('date_expire'),
                ]);

            //devolver hacia atras
            return back();
        }
    }

    /**
     * @return Collection
     */
    public function getAll():Collection
    {
        //Retornar todas las secuencias registrada
        return Sequence::orderBy('type')->get();
    }


    /**
     * @param SequenceTypeEnum $type
     * @return JsonResponse
     */
    public function get(SequenceTypeEnum $type):JsonResponse
    {
        //retonar el primer elemento solicitado
        $sequence = Sequence::where('type', $type)->first();

       //Verificar si la secuancia existe
        if (!$sequence) {
            return response()->json([
                'error' => 'El tipo de Secuancia no existe.'
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
        $path = public_path("storage/rncs/DGII_RNC.TXT");

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

}

<?php

namespace App\Http\Controllers;

use App\Enums\SequenceTypeEnum;
use App\Helpers\SequenceHelper;
use App\Http\Requests\SequenceRequest;
use App\Models\Sequence;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SequenceController extends Controller
{
    /**
     * Vista para la secuencia de RNC
     * @return Response
     */
    public function create()
    {
        //Intancia
        $sequenceHelper = new SequenceHelper();


        //DEvolver la vista con los datos
        return Inertia::render('Setting/Sequence',[
            'sequenceType' => config('appconfig.sequence'),
            'sequencesData' => $sequenceHelper->getAll()

        ]);
    }


    /**
     * @param SequenceRequest $request
     * @return RedirectResponse
     */
    public function store(SequenceRequest $request)
    {

        //Intancia
        $sequenceHelper = new SequenceHelper();


        //Llamar el metodo
        return $sequenceHelper->store($request);



    }

    /**
     * @param Sequence $sequence
     * @return Response
     */
    public function edit(Sequence $sequence): Response
    {

        //Intancia
        $sequenceHelper = new SequenceHelper();

        //DEvolver la vista con los datos
        return Inertia::render('Setting/Sequence',[
            'sequenceType' => config('appconfig.sequence'),
            'sequencesData' => $sequenceHelper->getAll(),
            'sequenceEdit' => $sequence

        ]);

    }

    /**
     * @param Sequence $sequence
     * @return RedirectResponse
     */
    public function destroy(Sequence $sequence)
    {

        //Colocar la secuencia en elimionada
        $sequence->update([
            'deleted_at' => Carbon::now()
        ]);

        //Retornar hacia atras
        return back();
    }

    /**
     * @param SequenceTypeEnum $type
     * @return JsonResponse
     */
    public function get(SequenceTypeEnum $type)
    {
        //Intancia
        $sequenceHelper = new SequenceHelper();

        //Obtener el comprobantes elegido
        return $sequenceHelper->get($type);

    }


    /**
     * @param string $rnc
     * @return JsonResponse
     */
    public function getRnc(string $rnc)
    {
        //Intancia
        $sequenceHelper = new SequenceHelper();

        //Obtener los datos solicitado
        return $sequenceHelper->getRnc($rnc);


    }



}

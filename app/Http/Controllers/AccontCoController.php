<?php

namespace App\Http\Controllers;

use App\Enums\ACOEnum;
use App\Models\ACO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;
use Inertia\Response;

class AccontCoController extends Controller
{

    /**
     * @return Response
     */
    public function index():Response
    {

        // Vista con lso datos
        return Inertia::render('Setting/ACO/ACOCreate',[
            'aco' => ACO::all()
        ]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request):RedirectResponse
    {
        //Validar los datos
        $request->validate([
            'code' => ['required','string','max:30'],
            'name' => ['required','string','max:75'],
            'type' => ['required', new Enum(ACOEnum::class)]
        ]);

        //Guardar los datos
        ACO::create($request->toArray());

        // Devuelta hacia atras
        return back();
    }

    /**
     * @param Request $request
     * @param ACO $aco
     * @return RedirectResponse
     */
    public function update(Request $request,ACO $aco)
    {
        // Validar los datos
        $request->validate([
            'code' => ['required','string','max:30', Rule::unique('account_cos')->ignore($aco->id)],
            'name' => ['required','string','max:75'],
            'type' => ['required', new Enum(ACOEnum::class)]
        ]);


        //Actualizar todos los datos
        $aco->update($request->toArray());


        // Retornar hacia atras
        return back();
    }

    /**
     * @param ACO $aco
     * @return RedirectResponse
     */
    public function destroy(ACO $aco):RedirectResponse
    {
        //Eliminar
        $aco->delete();

        //Devolver hacia atras
        return back();
    }
}

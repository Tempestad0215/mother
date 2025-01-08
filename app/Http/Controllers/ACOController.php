<?php

namespace App\Http\Controllers;

use App\Enums\ACOEnum;
use App\Models\ACO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;
use Inertia\Response;

class ACOController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request):Response
    {
        //Busqueda de datos
        $search = $request->get('search');
        $perPage = $request->get('perPage');

        $aco = ACO::where(function (Builder $query) use ($search) {
            $query->where('code',$search)
                ->orWhere('name','like','%'.$search.'%');
        })->orderByDesc('created_at')
            ->simplePaginate($perPage);


        // Vista con lso datos
        return Inertia::render('ACO/ACOCreate',[
            'aco' => $aco
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

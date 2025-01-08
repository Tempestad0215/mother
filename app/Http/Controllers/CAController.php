<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CAController extends Controller{
    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){

        //Tomar los datos de busqueda
        $data = $this->get($request);

        //Devolver la vista con los datos
        return Inertia::render('Categories/CategoryCreate',[
            'categories' => $data
            ]);

    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public  function store(Request $request)
    {

        //Validar los datos
        $request->validate([
            'name' => ['required','string','min:3','max:70'],
            'description' => ['nullable','string','max:255'],
        ]);

        // Crear los datos
        Category::create($request->only(['name','description']));

        //Devolver hacia atras
        return back();


    }


    /**
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(Request $request, Category $category){
        // Valiar los datos antes de actualizar
        $request->validate([
            'name' => ['required','string','min:3','max:70'],
            'description' => ['nullable','string','max:255'],
        ]);

        // Actualizar los datos
        $category->update($request->only(['name','description']));

        //Devolver hacia atras
        return back();

    }


    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category){


        // Actualizar los datos
        $category->update([
            'deleted_at' => now()
        ]);
        //Devolver hacia atras
        return back();

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getJson(Request $request){

        //Tomar los datos de busqueda
        $search = $request->get('search');


        // Tomar los datos y limitarlo a 10
        $data = Category::where('status',true)
            ->where('name', 'like', '%'.$search.'%')
            ->limit(10)
            ->get();

        //Devolver un datos en json
        return response()->json($data);

    }


    /**
     * @param Request $request
     * @return Paginator
     */
    private function get(Request $request):Paginator
    {

        // Tomar los datos de busqueda
        $search = trim($request->get('search'));
        $perPage = $request->get('perPage',15);

        return Category::search($search)
            ->where('status',true)
            ->latest('created_at')
            ->simplePaginate($perPage);

    }



}

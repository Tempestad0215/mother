<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CategoryController extends Controller{
    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request){

        //Tomar los datos de busqueda
        $data = $this->get($request);

        //Devolver la vista con los datos
        return Inertia::render('Categories/Create',[
            'categories' => $data
            ]);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public  function store(Request $request){

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
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category){

        //Poner la restricciones de usuario
        Gate::authorize('delete', Auth::user());
        // Actualizar los datos
        $category->update([
            'status' => true
        ]);
        //Devolver hacia atras
        return back();

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJson(Request $request){

        //Tomar los datos de busqueda
        $search = $request->get('search');


        // Tomar los datos y limitarlo a 10
        $data = Category::where('status',false)
            ->where('name', 'like', '%'.$search.'%')
            ->limit(10)
            ->get();

        //Devolver un datos en json
        return response()->json($data);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    private function get(Request $request)
    {

        // Tomar los datos de busqueda
        $search = $request->get('search');



        $data = Category::where('status',false)
            ->where('name', 'like', '%'.$search.'%')
            ->latest()
            ->simplePaginate(15);

        return $data;
    }



}

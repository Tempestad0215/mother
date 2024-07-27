<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller{
    public function create(Request $request){
        try {


            $data = $this->get($request);

            return Inertia::render('Categories/Create',[
                'categories' => $data
            ]);

        }catch(\Exception $th){
            throw $th;
        }
    }

    public  function store(Request $request){
        try {
            $request->validate([
                'name' => ['required','string','min:3','max:70'],
                'description' => ['nullable','string','max:255'],
            ]);

            Category::create($request->only(['name','description']));

            return back();

        }catch (\Exception $th){
            throw $th;
        }
    }

    public function update(Request $request, Category $category){
        try {

            $request->validate([
                'name' => ['required','string','min:3','max:70'],
                'description' => ['nullable','string','max:255'],
            ]);

            $category->update($request->only(['name','description']));

            return back();

        }catch (\Exception $th){
            throw $th;
        }
    }

    public function destroy(Category $category){
        try {

            $category->update([
                'status' => true
            ]);

            return back();

        }   catch (\Exception $th){
            throw $th;
        }
    }


    public function getJson(Request $request){
        try {
            $search = $request->get('search');

            $data = Category::where('status',false)
                ->where('name', 'like', '%'.$search.'%')
                ->limit(10)
                ->get();

            return response()->json($data);


        }catch (\Exception $th){
            return response()->json(['error'=> 'Error en esta peticion']);
        }
    }

    private function get(Request $request)
    {
        $search = $request->get('search');

        $data = Category::where('status',false)
            ->where('name', 'like', '%'.$search.'%')
            ->latest()
            ->simplePaginate(15);

        return $data;
    }



}

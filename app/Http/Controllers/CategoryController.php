<?php

namespace App\Http\Controllers;

use App\Exports\CategoryExport;
use App\Models\Category;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;

class CategoryController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth'),
            new Middleware('role:Super Admin|Supervisor'),
        ];
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){

        $request->validate([
            'search' => 'nullable|string',
            'per_page'=> 'nullable|numeric|min:1|max:50',
            'page'=> 'nullable|numeric|min:1',
        ]);


        $search = $request->search;
        $query = Category::query();

        if ($search)
        {
            $query->where('name', 'ILIKE', "%$search%")
                ->orWhere('description', 'ILIKE', "%$search%");
        }

        $categories = $query->paginate($request->per_page)->withQueryString();

        //Devolver la vista con los datos
        return Inertia::render('Categories/Register',[
            'categories' => $categories,
            ]);

    }


    private function createPrefix(string $name):string
    {
//        Limiar la cadena y convertir a Mayuscula
        $cleanName = Str::upper(Str::trim(Str::replace(" ","", $name)));

//        tomar los primero 4 digitos
        $prefix = Str::substr($cleanName, 0 , 3);
        $originalPrefix = $prefix;
        $counter = 1;


        while(Category::where('prefix', $prefix)->exists()){
            if (Str::length($cleanName) >= 4 && $counter === 1) {
                $prefix = Str::substr($cleanName, 0, 3);
                $counter ++;
            }else{
                $prefix = $originalPrefix.$counter;
                $counter ++;
            }
        }

        return $prefix;

    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public  function store(Request $request)
    {

        //Validar los datos
        $validated = $request->validate([
            'name' => ['required','string','min:3','max:70'],
            'description' => ['nullable','string','max:255'],
        ]);

        $dataForInsert = $validated;
        $dataForInsert['prefix'] = self::createPrefix($request->input('name'));
        // Crear los datos
        Category::create($dataForInsert);

        //Devolver hacia atras
        return back();


    }


    /**
     * Editar los datos de la categorias
     * @param Category $category
     * @param Request $request
     * @return Response
     */
    public function edit(Category $category, Request $request)
    {
//        Tomar los datos para la busqueda
        $data = $this->get($request);

//        Devolver la vista con los datos
        return Inertia::render('Categories/Register',[
            'categories' => $data,
            'categoryEdit' => $category,
            'update' => true
        ]);
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
        $category->delete();
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
        $per_page = $request->get('per_page',15);
        $field = $request->get('field','name');

        return Category::query()
            ->where($field,'like','%'.$search.'%')
            ->where('status',true)
            ->latest('created_at')
            ->simplePaginate($per_page);

    }


    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel()
    {

        return Excel::download(new CategoryExport, 'categorias.xlsx');
    }



}

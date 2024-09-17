<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Enums\ProductTransType;
use App\Helpers\CategoryHelper;
use App\Helpers\InHelper;
use App\Helpers\TransHelper;
use App\Http\Resources\ProductTransResource;
use App\Models\Product;
use App\Http\Requests\StoreProductInRequest;
use App\Http\Requests\UpdateProductInRequest;
use App\Models\ProTrans;
use App\Services\configService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Inertia;
use Inertia\Response;
use function PHPUnit\Framework\throwException;

class ProductInController extends Controller
{

    protected configService $configService;

    public function __construct()
    {
        $this->configService = new configService();
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        //conseguir  los datos
        $data = $this->getProduct($request);

        //Devolver la vista con los datos
        return Inertia::render('ProductsIn/In', [
            'products' => $data,

        ]);


    }

    /**
     * @return void
     */
//    public function create()
//    {
//
//    }

    /**
     * @param StoreProductInRequest $request
     * @param Product $productIn
     * @return RedirectResponse
     */
    public function store(StoreProductInRequest $request, Product $productIn): RedirectResponse
    {


        DB::transaction(function () use ($request, $productIn) {

            //Actulizar los datos
            $inHelper = new inHelper();
            $transHelper = new TransHelper();

            //Actualizar los datos del producto
            $inHelper->updateProduct($request, $productIn);

            //Crear los datos de la transaccion
            $transHelper->store($request, ProductTransType::ENTRADA, $productIn->id);


        });

        //Devolver hacia atras
        return back();

    }

    /**
     * Display the specified resource.
     */
    // public function show(ProductIn $productIn)
    // {
    //     //
    // }

    /**
     * @param Request $request
     * @param Product $productIn
     * @return Response
     */
    public function entrance(Request $request, Product $productIn): Response
    {

        // Tomar lo datos de todos los produtos
        $data = $this->getProduct($request);

        // Devolver la vista con los datos
        return Inertia::render('ProductsIn/In', [
            'products' => $data,
            'productEntrance' => $productIn,
        ]);

    }

    /**
     * @param Request $request
     * @param ProTrans $trans
     * @return Response
     */
    public function edit(Request $request, ProTrans $trans): Response
    {

        //conseguir  los datos
        $data = $this->getProduct($request);

        //Devolver la vista con los datos
        return Inertia::render('ProductsIn/In', [
            'trans' => new ProductTransResource($trans),
            'products' => $data,
            'update' => true
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {

        //Instancia
        $inHelper = new inHelper();

        //Tomar los datos
        $data = $inHelper->getTransIn($request);


        //Devolver la vista con los datos
        return Inertia::render('ProductsIn/ShowTrans', [
            'trans' => $data
        ]);
    }

    /**
     * @param UpdateProductInRequest $request
     * @param ProTrans $trans
     * @return RedirectResponse|void
     */
    public function update(UpdateProductInRequest $request, ProTrans $trans)
    {
        //        Tomar las fechas
        $updateDay = config('appconfig.document-update');

        // Formatear la fecha de creacion
        $createdAtLimit  = Carbon::parse($trans->created_at)->addDays($updateDay);

        // tomar la fecha actual
        $now = Carbon::now();

        // si el parametro de actualiacion es mayor  a updated at
        if($createdAtLimit->lessThan($now))
        {

            //Mensaje de error
            return back()->withErrors([
                'general' => 'El Documento de ID:'.$trans->id.' Esta Fuera Del Rango De Fecha Permitido Para Actualizar Documento'
            ]);

        }else{

            DB::transaction(function () use ($request, $trans) {

                //Instancia
                $inHelper = new Inhelper();
                $transHelper = new TransHelper();


                //conseguir los datos del producto
                $product = Product::find($trans->product_id);


                //Actualizar la transaciom
                $transHelper->store($request, ProductTransType::AJUSTE, 0, $product->id);
                //Actualizar los productos
                $inHelper->adjustProduct($request, $product);
            });

            //Actualizar todos los datos

        }

    }

    /**
     * @param ProTrans $trans
     * @return RedirectResponse
     */
    public function destroy(ProTrans $trans)
    {

        //dia para eliminar documento
        $deleteDate = config('appconfig.document-delete');

        //limite para eliminar documento
        $createDeleteLimit = Carbon::parse($trans->created_at)->addDays($deleteDate);
        $now = Carbon::now();

        if($createDeleteLimit->lessThan($now))
        {
            return back()->withErrors([
                'general' => 'Este Docuemento No Puede Ser Eliminado Excede La Fecha Limite'
            ]);
        }else{

            //Actualizar los datos
            $trans->update([
                'status' => false
            ]);

            //Retornar hacia atras
            return back();
        }

    }



    /**
     * @param Request $request
     * @return Paginator
     */
    private function getProduct(Request $request):Paginator
    {
        //Obtener los datos de busqueda
        $search = $request->get('search');

        //Devolver los datos
        return Product::where('status', true)
            ->where('type', 'producto')
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->simplePaginate();

    }
}

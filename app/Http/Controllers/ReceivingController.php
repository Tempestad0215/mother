<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseStatusEnum;
use App\Http\Resources\PurchaseSupplierResource;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReceivingController extends Controller
{
    public function index(Request $request)
    {

        $validate = $request->validate([
            'supplier' => ['nullable','numeric','exists:suppliers,id']
        ]);

        $supplier = new SupplierController();
        $purchaseAvailable = null;
        $supplierId = $request->get('supplier');


        if (!$supplierId !== null)
        {
            $purchaseAvailable = $this->getPurchaseAvailable((int)$supplierId);
        }



        return Inertia::render('Purchase/Receiving', [
            'purchases' => $this->getPurchaseApprove($request),
            'suppliers' => $supplier->get($request),
            'purchaseAvailable' => $purchaseAvailable
        ]);
    }


    private function getPurchaseApprove(Request $request)
    {
        $validate = $request->validate([
            'search' => ['nullable','max:80','string']
        ]);

        $search = $request->get('search','');


        $purchases = Purchase::query()
            ->with('supplier')
            ->with('items')
            ->where('status', PurchaseStatusEnum::Pendiente)
            ->when($search !== '', function ($query, $search) {
                $query->whereHas('supplier', function ($q2) use ($search) {
                    $q2->where('company_name', 'like', '%' . $search . '%')
                    ->orWhere('contact', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
                });
            })->paginate(15);


        return PurchaseSupplierResource::collection($purchases);
    }


    public function getPurchaseAvailable(int  $supplierId)
    {
        $supplier = Purchase::query()
            ->with(['supplier','items.product'])
            ->whereHas('supplier', function ($q) use ($supplierId) {
                $q->where('id', $supplierId);
            })->where('status', PurchaseStatusEnum::Pendiente)->get();

        return PurchaseSupplierResource::collection($supplier);

    }
}

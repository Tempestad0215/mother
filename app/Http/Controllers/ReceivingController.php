<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseStatusEnum;
use App\Http\Resources\PurchaseSupplierResource;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReceivingController extends Controller
{
    public function index(Request $request)
    {
        $supplier = new SupplierController();


        return Inertia::render('Purchase/Receiving', [
            'purchases' => $this->getPurchaseApprove($request),
            'suppliers' => $supplier->get($request),
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
}

<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\SaleItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property Product $product
 * @mixin SaleItem
 */
class SaleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Obtener la configuracion
        $setting = Setting::getGlobal();

        // Verificar si se va agregar el itbis o no
        if($setting && $setting->add_tax)
        {
            $taxPlus = bcadd($this->tax_rate, 1);
            $productWithTax = bcmul((string)$this->price,$taxPlus);
            $tax = bcmul($productWithTax, $this->stock);
        }else{
            $taxProduct =  bcmul((string)$this->price,$this->tax_rate);

            $tax = bcmul($this->stock, $taxProduct);
        }


        return [
            ...parent::toArray($request),
            'price_type' => $this->price_type,
            'temp_price' => $this->price,
            'product_name' => $this->product?->name,
            'warehouse_uuid' => $this->warehouse()->where('uuid', $this->warehouse_uuid)->first()->uuid,
            'tax_uuid' => $this->product?->tax_uuid,
            'price_list' => $this->product?->default_price_list,
            'tax_amount' => $tax,
        ];
    }
}

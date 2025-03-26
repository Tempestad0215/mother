<?php

namespace App\Http\Resources;

use App\Enums\ProductTypeEnum;
use App\Enums\TransTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProTrans;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * @property int id
 * @property string invoice_type
 * @property string ncf
 * @property string ncf_m
 * @property string client_name
 * @property int client_id
 * @property float discount_amount
 * @property float tax
 * @property float sub_total
 * @property float amount
 * @property boolean status
 * @property SaleTypeEnum type
 * @property bool close_table
 * @property Carbon created_at,
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @property ProTrans[] infoSale
 * @property ProTrans[] sale
 * @property ProTrans[] trans
 * @property Comment comment
 */

class SaleCreditNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        //Convertir al collectio
        $infoCollect = collect($this->infoSale)->filter(function ($item) {
           return $item['type'] === TransTypeEnum::VENTAS;
        });

        //Para pasar los datos
        $info = [];

        //Recorrer los datos
        $infoCollect->map(function (ProTrans $item) use (&$info) {

            //Obtener los productos que tengan devolucion pendiente
            $transProduct = ProTrans::where('product_id', $item->product_id)
                ->where('sale_id', $item->sale_id)
                ->where('type', TransTypeEnum::DEVOLUCION)
                ->where('status', true)
                ->get();

            //Obtener el primer registro
            //TODO para comparar
//            $productFirst = $transProduct->first();
            //Tomar el producto para poner los datos
            $productFirst = Product::find($item->product_id);

            //Tomar el valor del stock para la devolucion, si es 0 pues no se incluye
            $stockAmount = $item['stock'] - $transProduct->sum('stock');

            //Verificar si existe
            if ($transProduct->isEmpty()) $stockAmount = $item["stock"];


            //Verificar si el stock == 0
            if ($stockAmount > 0) {

                //Crear la informacion
                $info[] = [
                    'id' => $item['id'],
                    'sale_id' => $item['sale_id'],
                    'product_id' => $productFirst->id,
                    'credit_note_id' => null,
                    'product_name' => $productFirst->name,
                    'stock' => $stockAmount ?: $item['stock'],
                    'price' => $item['price'],
                    'special_price' => $item['special_price'],
                    'min_price' => $item['min_price'],
                    'tax_rate' => $item['tax_rate'],
                    'tax' => $item['tax'],
                    'amount' => $item['amount'],
                    'discount' => $item['discount'],
                    'discount_amount' => $item['discount_amount'],
                    'type' => $productFirst->type,
                    'status' => $item['status']
                ];
            }
        });

//


        //verificar si esta vacio la info
        if (count($info) == 0)
        {
            throw ValidationException::withMessages([
                'general' => "Este Documento No Tiene Item Disponible Para NC"
            ]);
        }

        //Devolver los datos formateado
        return [
            "id" => $this->id,
            "invoice_type" => $this->invoice_type,
            "ncf" => $this->ncf,
            "ncf_m" => $this->ncf_m,
            "client_name" => $this->client_name,
            "client_id" => $this->client_id,
            "discount_amount" => $this->discount_amount,
            "tax" => $this->tax,
            "sub_total" => $this->sub_total,
            "amount" => $this->amount,
            "status" => $this->status,
            "type" => $this->type,
            "close_table" => $this->close_table,
            "info_sale" => $info,
        ];
    }
}

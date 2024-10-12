<?php

namespace App\Http\Resources;

use App\Enums\ProductTransType;
use App\Enums\SaleTypeEnum;
use App\Models\Comment;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\ProTrans;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * @property int $id
 * @property string $invoice_type
 * @property string $ncf
 * @property string $ncf_m
 * @property string $code
 * @property string $client_name
 * @property int $client_id
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property boolean $status
 * @property SaleTypeEnum $type
 * @property bool $close_table
 * @property Carbon $created_at,
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property ProTrans[] $sale
 * @property ProTrans[] $trans
 * @property Comment $comment
 */

class SaleCreditNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        //Convertir al collectio
        $infoCollect = collect($this->infoSale);


        //Para pasar los datos
        $info = [];

        //Recorrer los datos
        $infoCollect->map(function ($item, $key) use (&$info) {

            //Obtener producto
            $product = Product::whereHas('trans', function (Builder $q) {
                $q->whereIn('type', [ProductTransType::VENTAS, ProductTransType::RESERVA])
                    ->where('status', true);
            })->get();


            // Conseguir la transaccion de venta
            $creditSale = CreditNote::whereHas('trans', function (Builder $q) use ($item) {
                $q->where('product_id', $item['product_id']);
            })->where('sale_id', $item['sale_id'])
                ->first();

            //Conseguir la transacciones con devolucion existente
            $transRet = ProTrans::where('product_id', $item['product_id'])
                ->where('sale_id', $item['sale_id'])
                ->whereHas('credit_note')
                ->whereIn('type', [ProductTransType::DEVOLUCION])
                ->where('stock', true)
                ->first();

            //Convetir a collection

            //Verificar si es true o false
            $existsTrans = (bool)$creditSale;

            //Tomar el stock de ;a
            $creditStock = $creditSale ? $creditSale->trans[$key]->stock : 0;
            //Tomar los resultado de stock
            $stockRet = $transRet ? $transRet->stock : 0;

            //Tomar el resultado
            $result = $creditStock - $stockRet;

            //PAra contrar la informacion
            $countSale = count($info);

            //Si el item tiene disponible
            if ($existsTrans && $item['type'] != ProductTransType::DEVOLUCION && $product != null)
            {

                //Crear la informacion
                $info[] = [
                    "id" => $item['id'],
                    "code" => $product[$key]->code ?: "",
                    "sale_id" => $item['sale_id'],
                    "product_id" => $product[$key]->id,
                    "credit_note_id" => null,
                    "product_name" => $product[$key]->name,
                    "stock" => $result == 0 ? $item['stock'] : abs($result),
                    "price" => $item['price'],
                    "tax_rate" => $item['tax_rate'],
                    "tax" => $item['tax'],
                    "amount" => $item['amount'],
                    "discount" => $item['discount'],
                    "discount_amount" => $item['discount_amount'],
                    "type" => SaleTypeEnum::VENTAS->value,
                    "status" => $item['status'],
                ];
            }else if (!isset($transRet) && $countSale < 1 && $product != null )
            {
                //Enviar mensaje cuando no haya producto disponible para realizar la nota de credito
                throw  ValidationException::withMessages([
                    'general' => "Este Documento No Tiene Item Disponible Para NC"
                ]);
            }else{
                echo('paso por aqui');
            }
        });

        dd('final');


        //Devolver los datos formateado
        return [
            'id' => $this->id,
            'invoice_type' => $this->invoice_type,
            'ncf' => $this->ncf,
            'ncf_m' => $this->ncf_m,
            'code' => $this->code,
            'client_name' => $this->client_name,
            'client_id' => $this->client_id,
            'discount_amount' => $this->discount_amount,
            'tax' => $this->tax,
            'sub_total' => $this->sub_total,
            'amount' => $this->amount,
            'status' => $this->status,
            'type' => $this->type,
            'close_table' => $this->close_table,
            'info_sale' => $info,

        ];

    }
}

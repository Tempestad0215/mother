<?php

namespace App\Http\Requests;

use App\Enums\SalePaymentEnum;
use App\Enums\SaleTypeEnum;
use App\Models\Setting;
use App\Rules\CheckStock;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class  StoreProductSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {

        //datos de configuracion
        $sequence = Setting::pluck('sequence')->first() ??  false;
        $invoice_type = $this->get('invoice_type');
        $type = $this->get('type');



        //Tomar los datos de la info_sale
        $info_sale = $this->input('info_sale');

        // Crear la validacion de los datos
        return [
            'id' => ['nullable', 'numeric'],
            'ncf' => ['nullable','string','max:30',Rule::requiredIf($sequence)],
            'ncf_m' => ['nullable','string','max:30',Rule::requiredIf($sequence && $invoice_type == "B04")],
            'invoice_type' => ['nullable','max:6','string', Rule::requiredIf($sequence)],
            'client_name' => ['nullable', 'string','min:3','max:75'],
            'client_id' => ['nullable','integer'],
            'client_rnc' => ['nullable','string','max:20'],
            'info_sale' => ['required','array', new CheckStock($info_sale)],
            'info_sale.*.id' => ['required','numeric','exists:products,id'],
            'info_sale.*.code' => ['nullable','string','min:4','max:50'],
            'info_sale.*.product_name' => ['required','string','min:3','max:75'],
            'info_sale.*.stock' => ['required','numeric'],
            'info_sale.*.price' => ['required','numeric'],
            'info_sale.*.tax' => ['required','numeric'],
            'info_sale.*.tax_rate' => ['required','numeric'],
            'info_sale.*.amount' => ['required','numeric'],
            'info_sale.*.discount' => ['required','numeric'],
            'info_sale.*.discount_amount' => ['required','numeric'],
            'tax' => ['required','numeric'],
            'amount' => ['required','numeric'],
            'sub_total' => ['required','numeric'],
            'discount_amount' => ['required','numeric'],
            'type' => ['required',Rule::enum(SaleTypeEnum::class)],
            'type_payment' => ['nullable',Rule::requiredIf(SaleTypeEnum::DEVOLUCION->value !== $type) ,Rule::enum(SalePaymentEnum::class)],
            'received' => ['required','numeric'],
            'returned' => ['required','numeric'],
            'comment' => ['nullable','string','min:3','max:255'],
            'close_table' => ['required','boolean'],
        ];
    }
}

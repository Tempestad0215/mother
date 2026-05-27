<?php

namespace App\Http\Requests;

use App\Enums\PaymentTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Models\Setting;
use App\Rules\CheckStock;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Route;




class  StoreProductSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function isTypePaymentRequired(): bool
    {
        $type = $this->input('type'); // o $this->type

        return $type !== SaleTypeEnum::Devolucion->value
            && $type !== SaleTypeEnum::Cotizacion->value;
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



        // Crear la validacion de los datos
        return [
            'uuid' => ['nullable', 'string','uuid'],
            'ncf' => ['nullable','string','max:30',Rule::requiredIf($sequence)],
            'invoice_type' => ['nullable','max:6','string', Rule::requiredIf($sequence)],
            'client_name' => ['nullable', 'string','min:3','max:75'],
            'client_id' => ['nullable','integer'],
            'client_rnc' => ['nullable','string','max:20'],
            'info_sale' => ['required','array', new CheckStock()],
            'info_sale.*.product_uuid' => ['required','uuid','exists:products,uuid'],
            'info_sale.*.code' => ['nullable','string','min:4','max:50'],
            'info_sale.*.product_name' => ['required','string','min:3','max:75'],
            'info_sale.*.stock' => ['required','numeric'],
            'info_sale.*.price' => ['required','numeric'],
            'info_sale.*.temp_price' => ['required','numeric'],
            'info_sale.*.tax_uuid' => ['required','uuid','exists:taxes,uuid'],
            'info_sale.*.warehouse_uuid' => ['required','uuid','exists:warehouses,uuid'],
            'info_sale.*.tax_rate' => ['required','numeric'],
            'info_sale.*.amount' => ['required','numeric'],
            'info_sale.*.discount' => ['required','numeric'],
            'info_sale.*.discount_amount' => ['required','numeric'],
            'tax' => ['required','numeric'],
            'amount' => ['required','numeric'],
            'sub_total' => ['required','numeric'],
            'discount_amount' => ['required','numeric'],
            'type' => ['required',Rule::enum(SaleTypeEnum::class)],
            'type_payment' => ['nullable',Rule::requiredIf($this->isTypePaymentRequired()) ,Rule::enum(PaymentTypeEnum::class)],
            'received' => ['required','numeric'],
            'returned' => ['required','numeric'],
            'credit_notes' => ['nullable','array'],
            'credit_notes.*.uuid' => ['nullable','uuid'],
            'credit_notes_amount' => ['nullable','numeric'],
            'comment' => [Rule::requiredIf(Route::is('credit-note.store')),'max:255'],
            'close_table' => ['required','boolean'],
        ];
    }


}

<?php

namespace App\Http\Requests;

use App\Enums\PATYEnum;
use App\Enums\SATYEnum;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        //datos de configuracion
        $sequence = Setting::pluck('sequence')->first() ??  false;

        //Obtener el tipo de venta
        $type = $this->get('type');

        //Tomar los datos de la info_sale
        $info_sale = $this->input('info_sale');

        // Crear la validacion de los datos
        return [
            'id' => ['nullable', 'string','uuid'],
            'ncf' => ['nullable','string','max:30',Rule::requiredIf($sequence)],
            'invoice_type' => ['nullable','max:6','string', Rule::requiredIf($sequence)],
            'client_name' => ['nullable', 'string','min:3','max:75'],
            'client_id' => ['nullable','string','uuid'],
            'client_rnc' => ['nullable','string','max:20'],
            'info_sale' => ['required','array', new CheckStock($info_sale)],
            'info_sale.*.product_id' => ['required','string','exists:products,uuid'],
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
            'type' => ['required',Rule::enum(SATYEnum::class)],
            'type_payment' => ['nullable',Rule::requiredIf(SATYEnum::DEVOLUCION->value !== $type) ,Rule::enum(PATYEnum::class)],
            'received' => ['required','numeric'],
            'returned' => ['required','numeric'],
            'credit_notes' => ['nullable','array'],
            'credit_notes.*.id' => ['nullable','string','uuid'],
            'credit_notes_amount' => ['nullable','numeric'],
            'comment' => [Rule::requiredIf(Route::is('credit-note.store')),'max:255'],
            'close_table' => ['required','boolean'],
        ];
    }
}

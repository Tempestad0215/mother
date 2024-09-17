<?php

namespace App\Http\Requests;

use App\Rules\CheckStock;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductSaleRequest extends FormRequest
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

        //Tomar los datos de la info
        $info = $this->input('info');

        // Crear la validacion de los datos
        return [
            'id' => ['nullable', 'numeric'],
            'client_name' => ['nullable', 'string','min:3','max:75'],
            'client_id' => ['nullable','integer'],
            'info' => ['required','array', new CheckStock($info)],
            'info.*.id' => ['required','numeric','exists:products,id', ],
            'info.*.name' => ['required','string','min:3','max:75'],
            'info.*.quantity' => ['required','numeric'],
            'info.*.cost' => ['required','numeric'],
            'info.*.price' => ['required','numeric'],
            'info.*.tax' => ['required','numeric'],
            'info.*.tax_rate' => ['required','numeric'],
            'info.*.tax_amount' => ['required','numeric'],
            'info.*.amount' => ['required','numeric'],
            'info.*.discount' => ['required','numeric'],
            'info.*.discount_amount' => ['required','numeric'],
            'info.*.product_tax' => ['required','numeric'],
            'tax' => ['required','numeric'],
            'amount' => ['required','numeric'],
            'sub_total' => ['required','numeric'],
            'discount_amount' => ['required','numeric'],
            'comment' => ['nullable','string','min:3','max:255'],
            'close_table' => ['required','boolean'],
        ];
    }
}

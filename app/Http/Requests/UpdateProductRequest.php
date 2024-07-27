<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','min:3','max:75'],
            'description' => ['nullable','string','max:150'],
            'unit' => ['required','string',],
            'supplier_id' => ['required','numeric','exists:suppliers,id'],
            'category_id' => ['required','numeric','exists:categories,id'],
            'bar_code' => ['nullable','string','max:100'],
            'sku' => ['nullable','string','max:75'],
            'tax_rate' => ['required','numeric'],
            'branch' => ['nullable','string','max:75'],
            'weight' => ['nullable','numeric'],
            'dimensions' => ['nullable','string','max:255'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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


        return [
            'contact' => ['nullable','string','max:75'],
            'company_name' => ['required','string','min:3','max:75'],
            'phone' => ['nullable','string','max:25'],
            'email' => ['nullable','string','max:150',Rule::unique('suppliers')->ignore($this->route('supplier'))],
        ];
    }
}

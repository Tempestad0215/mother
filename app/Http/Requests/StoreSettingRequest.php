<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $logo
 * @property string $website
 * @property string $company_id
 * @property array $tax
 * @property array $unit
 * @property string $fiscal_year
 * @property boolean $status
 * @property boolean $save_cost
 */
class StoreSettingRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'company_id' => ['nullable', 'string', 'max:30'],
            'tax' => ['required','array'],
            'tax.*.name' => ['required','string','min:2','max:20'],
            'tax.*.amount' => ['required','numeric'],
            'unit' => ['required','array'],
            'unit.*' => ['nullable','string','min:2', 'max:30'],
            'fiscal_year' => ['nullable','date'],
        ];
    }
}

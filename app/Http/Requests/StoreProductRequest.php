<?php

namespace App\Http\Requests;

use App\Enums\PROTYEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
        $isArticle = $this->get('type') === 'producto';


        return [
            'inventoried' => ['required','boolean'],
            'name' => ['required','string','min:3','max:75'],
            'description' => ['nullable','string','max:150'],
            'unit' => [Rule::requiredIf($isArticle),'string','nullable'],
            'supplier_id' => ['required','uuid','exists:suppliers,uuid'],
            'category_id' => ['required','uuid','exists:categories,uuid'],
            'bar_code' => ['nullable','string','max:100'],
            'brand' => ['nullable','string','max:75'],
            'sku' => ['nullable','string','max:75'],
            'type' => [Rule::enum(PROTYEnum::class), 'required'],
            'tax_rate' => ['required','numeric'],
            'price' => ['required', 'numeric',Rule::notIn(0.00)],
            'weight' => ['nullable','numeric'],
            'dimensions' => ['nullable','string','max:255'],
        ];
    }
}

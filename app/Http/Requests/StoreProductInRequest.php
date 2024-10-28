<?php

namespace App\Http\Requests;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use App\Rules\CheckIsServiceRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @property Product $product_id
 * @property float $stock
 * @property float $cost
 * @property float $price
 * @property float $tax_rate
 * @property float $tax
 * @property float $amount
 * @property float $discount
 * @property float $discount_amount
 * @property  float $product_no_tax
 * @property  float $product_tax
 * @property float $benefits
 */
class StoreProductInRequest extends FormRequest
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
        //Para verificar si es servicio o no
        $product = Product::find($this->product_id);

        //Validar los datos
        return [
            'product_id' => ['required','exists:products,id'],
            'stock' => [Rule::requiredIf($product->type == ProductTypeEnum::PRODUCTO), 'required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'tax_rate' => ['required', 'numeric'],
            'tax' =>['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'discount' => ['nullable','numeric'],
            'discount_amount' => ['nullable','numeric'],
            'product_no_tax' => ['required','numeric'],
            'product_tax' => ['required','numeric'],
            'benefits' => ['required','numeric']
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'proccess' => ['required'],
            'supplier_id' => ['required'],
            'info' => ['required'],
            'amount' => ['required', 'numeric'],
            'tax' => ['required', 'numeric'],
            'sub_total' => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

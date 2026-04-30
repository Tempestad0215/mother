<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required'],
            'name' => ['required'],
            'currency' => ['nullable'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

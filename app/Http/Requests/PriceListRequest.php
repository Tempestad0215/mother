<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required','uuid'],
            'name' => ['required','string','min:3','max:30'],
            'currency' => ['nullable','string'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

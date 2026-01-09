<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable','string'],
            'per_page' => ['nullable','integer','min:15','max:100'],
            'page' => ['nullable','integer','min:1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

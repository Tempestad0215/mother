<?php

namespace App\Http\Requests;

use App\Enums\ClientDocumentEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientsRequest extends FormRequest
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
        // Tomar el id
        $id = $this->route('client');

        return [
            'name' => ['required','string','min:4','max:75'],
            'phone' => ['required','string','max:20'],
            'email'=> ['nullable','string','email','max:150',Rule::unique('clients','email')->ignore($id)],
            'address' => ['nullable','string','max:150'],
            'document' =>  ['required', Rule::enum(ClientDocumentEnum::class)],
        ];
    }
}

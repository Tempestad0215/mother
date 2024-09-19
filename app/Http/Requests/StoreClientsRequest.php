<?php

namespace App\Http\Requests;

use App\Enums\ClientTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientsRequest extends FormRequest
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

        //Tomar el tipo
        $type = (int) $this->request->get('type');
        //Convertir a true
        $isRequired = $type === 2 || $type === 3;
        $isAdvanced = $type === 3;
        $isCredit = $type === 2;

        return [
            'name' => ['required','string','min:4','max:75'],
            'phone' => ['string','max:20',Rule::requiredIf($isRequired)],
            'personal_id' => ['nullable','string','max:50',Rule::requiredIf($isRequired)],
            'email'=> ['nullable','string','email','max:150', Rule::unique('clients','email'),Rule::requiredIf($isRequired)],
            'address' => ['nullable','string','max:255',Rule::requiredIf($isRequired)],
            'type' => ['required', Rule::enum(ClientTypeEnum::class)],
            'status' => ['required','boolean'],

            //Validacion de los avance
            'advance_amount' => [Rule::requiredIf($isAdvanced),'nullable','numeric'],
            'advance_date' => [Rule::requiredIf($isAdvanced),'nullable','date'],
            'advance_expire' => ['nullable','date'],

            //Validacin de credito
            'credit_limit' => [Rule::requiredIf($isCredit),'nullable','numeric'],
            'credit_day' => [Rule::requiredIf($isCredit),'nullable','numeric','max:160'],
        ];
    }
}

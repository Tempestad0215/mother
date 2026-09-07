<?php

namespace App\Http\Requests;

use App\Enums\ClientDocumentEnum;
use App\Enums\ClientTypeEnum;
use App\Enums\ClientTypePriceEnum;
use App\Enums\SequenceSaleTypeEnum;
use App\Models\Client;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @mixin Client
 */
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {

        //Tomar el tipo
        $type = $this->input('type');

        // ✅ Correcto: Limpiar el string y comparar antes de asignar el booleano
        $isRequired = strtoupper(trim((string) $type)) !== 'CONTADO';


        return [
            'name' => ['required','string','min:4','max:75'],
            'phone' => ['nullable','max:20',Rule::requiredIf($isRequired)],
            'personal_id' => ['nullable','string','max:50',Rule::requiredIf($isRequired),Rule::unique('clients','personal_id')->ignore($this->route('client'))],
            'email'=> ['nullable','string','email','max:150',Rule::requiredIf($isRequired),Rule::unique('clients','email')->ignore($this->route('client'))],
            'address' => ['nullable','string','max:255',Rule::requiredIf($isRequired)],
            'type_rnc' => ['required',Rule::enum(SequenceSaleTypeEnum::class)],
            'type' => ['required', Rule::enum(ClientTypeEnum::class),'string'],
            'type_price' => [Rule::enum(ClientTypePriceEnum::class),'numeric','required'],
            'receive_email' => ['required','boolean'],
            'status' => ['required','boolean'],
            'document' =>  ['required', Rule::enum(ClientDocumentEnum::class)],
            'file' => ['nullable','file','mimes:png,jpg,jpeg','max:2048'],

            //Validación de los avance
            'amount' => [Rule::requiredIf($isRequired),'nullable','numeric'],
            'due_date' => [Rule::requiredIf($isRequired),'nullable','numeric'],
            'late_fee' => [Rule::requiredIf($isRequired),'nullable','numeric'],
            'comment' => ['nullable','string','max:255'],
        ];
    }
}

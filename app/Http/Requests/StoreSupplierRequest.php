<?php

namespace App\Http\Requests;

use App\Enums\PaymentTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @property string uuid
 * @property string|null contact
 * @property string company_name
 * @property string|null phone
 * @property PaymentTypeEnum type_payment
 * @property string|null email
 * @property bool receive_email
 * @property string account_bank
 * @property bool is_recurring
 * @property integer payment_day
 * @property boolean status
 */
class StoreSupplierRequest extends FormRequest
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

        // Para
        $isRequired = $this->type_payment == 'anticipo' || $this->type_payment == 'credito';


        return [
            'contact' => ['nullable','string','max:75'],
            'company_name' => ['required','string','min:3','max:75','unique:suppliers,company_name'],
            'phone' => ['nullable','string','max:25'],
            'type_payment' => ['required',Rule::enum(PaymentTypeEnum::class),'string'],
            'email' => ['nullable','string','max:150','unique:suppliers,email'],
            'account_bank' => ['string','nullable','max:30'],
            'receive_email' => ['required','bool'],
            'is_recurring' => ['required','bool'],
            'payment_day' => ['nullable', 'numeric'],
            'comment' => ['nullable','string','min:3','max:255'],

            //Validacion de los avance
            'amount' => [Rule::requiredIf($isRequired),'nullable','numeric'],
            'due_date' => [Rule::requiredIf($isRequired),'nullable','numeric'],
            'late_fee' => [Rule::requiredIf($isRequired),'nullable','numeric'],
        ];
    }
}

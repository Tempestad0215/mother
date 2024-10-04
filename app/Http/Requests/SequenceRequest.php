<?php

namespace App\Http\Requests;

use App\Enums\SequenceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;


/**
 * @property int $id
 * @property string $code
 * @property SequenceTypeEnum $type
 * @property int $from
 * @property int $next
 * @property int $to
 * @property string $num_request
 * @property string $num_authorization
 * @property Date $date_request
 * @property Date $date_authorization
 * @property Date $date_expire
 * @property boolean $status
 * @property Date $deleted_at
 */
class SequenceRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    /**
     * Datos de validacion
     * @return array
     */
    public function rules(): array
    {
        //Tomar el from para que el to siempre sea mayor que el from
       $from = $this->from + 1;
       //Restar el hasta - from
       $total = ($this->to - ($from - 1));


        return [
            'id' => ['nullable','integer'],
            'type' => ['required', Rule::enum(SequenceTypeEnum::class)],
            'from' => ['required','integer'],
            'to' => ['required','integer', "min:$from"],
            'advise' => ['required','integer', 'min:2',"max:$total"],
            'num_request' => ['required','string','max:30'],
            'num_authorization' => ['required','string','max:30'],
            'date_request' => ['required','date'],
            'date_expire' => ['nullable','date'],
        ];
    }
}

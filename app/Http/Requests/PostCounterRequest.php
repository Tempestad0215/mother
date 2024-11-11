<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCounterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from' => ['required','string'],
            'to' => ['required','string'],
            'coinFirst' => ['numeric'],
            'coinSecond' => ['numeric'],
            'coinThird' => ['numeric'],
            'coinFourth' => ['numeric'],
            'coinFifth' => ['numeric'],
            'coinSixth' => ['numeric'],
            'coinSeventh' => ['numeric'],
            'coinEighth' => ['numeric'],
            'coinNinth' => ['numeric'],
            'coinTenth' => ['numeric'],
            'card' => ['numeric'],
            'transfer' => ['numeric'],
            'check' => ['numeric'],
            'otherIncome' => ['numeric'],
            'expenses' => ['numeric'],
            'cashWithdrawals' => ['numeric'],
            'refund' => ['numeric'],
            'otherExpenses' => ['numeric'],
            'openingBalance' => ['numeric'],
            'totalCoin' => ['numeric'],
            'totalOtherMoney' => ['numeric'],
            'totalExpenses' => ['numeric'],
            'diff' => ['numeric'],
            'totalNeto' => ['numeric'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

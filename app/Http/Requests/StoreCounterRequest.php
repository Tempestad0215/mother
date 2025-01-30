<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


/**
 * @property string $from
 * @property string $to
 * @property float $coin_first
 * @property float $coin_second
 * @property float $coin_third
 * @property float $coin_fourth
 * @property float $coin_fifth
 * @property float $coin_sixth
 * @property float $coin_seventh
 * @property float $coin_eighth
 * @property float $coin_ninth
 * @property float $coin_tenth
 * @property float $card
 * @property float $transfer
 * @property float $check
 * @property float $other_income
 * @property float $expenses
 * @property float $cash_withdrawals
 * @property float $refund
 * @property float $other_expenses
 * @property float $opening_balance
 * @property float $total_coin
 * @property float $total_other_coin
 * @property float $total_expenses
 * @property float $diff
 * @property float $total_neto
 *
 */
class StoreCounterRequest extends FormRequest
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

        return [
            'coin_first' => ['numeric'],
            'coin_second' => ['numeric'],
            'coin_third' => ['numeric'],
            'coin_fourth' => ['numeric'],
            'coin_fifth' => ['numeric'],
            'coin_sixth' => ['numeric'],
            'coin_seventh' => ['numeric'],
            'coin_eighth' => ['numeric'],
            'coin_ninth' => ['numeric'],
            'coin_tenth' => ['numeric'],
            'card' => ['numeric'],
            'transfer' => ['numeric'],
            'check' => ['numeric'],
            'other_income' => ['numeric'],
            'expenses' => ['numeric'],
            'cash_withdrawals' => ['numeric'],
            'refund' => ['numeric'],
            'other_expenses' => ['numeric'],
            'opening_balance' => ['numeric'],
            'total_coin' => ['numeric'],
            'total_other_coin' => ['numeric'],
            'total_expenses' => ['numeric'],
            'diff' => ['required', 'numeric'],
            'total_neto' => ['required', 'numeric'],
        ];
    }
}

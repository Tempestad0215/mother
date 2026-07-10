<?php

namespace App\Enums;

enum CashMovementType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';
    case VAULT_DEPOSIT = 'vault_deposit';
    case INITIAL_FUND = 'initial_fund';
}

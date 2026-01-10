<?php

namespace Database\Factories;

use App\Models\ClientCredit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ClientCreditFactory extends Factory
{
    protected $model = ClientCredit::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

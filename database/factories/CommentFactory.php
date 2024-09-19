<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'content' => $this->faker->word(),
            'commentable_id' => $this->faker->randomNumber(),
            'commentable_type' => $this->faker->word(),
            'status' => $this->faker->boolean(),
            'created_at' => $this->faker->word(),
            'updated_at' => Carbon::now(),
        ];
    }
}

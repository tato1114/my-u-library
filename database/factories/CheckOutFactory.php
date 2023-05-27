<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckOut>
 */
class CheckOutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'check_out_date' => fake()->dateTimeBetween($startDate = '-1 month', $endDate = 'now')->format('Y-m-d'),
            'return_date' => fake()->dateTimeBetween($startDate = 'now', $endDate = '+1 month')->format('Y-m-d'),
            'status' => fake()->randomElement(['borrowed', 'returned'])
        ];
    }
}

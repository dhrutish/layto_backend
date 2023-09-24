<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedbacks>
 */
class FeedbacksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => 10,
            'user_id' => 12,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence,
            'type' => $this->faker->numberBetween(1, 2),
        ];
    }
}

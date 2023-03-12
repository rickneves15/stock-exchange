<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialAssetsFactory extends Factory
{
    public function definition()
    {
        return [
            'symbol' => $this->faker->unique()->regexify('[A-Z]{4}\d{2}'),
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['stock', 'fii', 'firf']),
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}

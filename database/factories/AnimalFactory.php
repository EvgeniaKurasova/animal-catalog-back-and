<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'name_en' => $this->faker->firstName(),
            'type' => 'dog',
            'type_en' => 'dog',
            'gender' => 1,
            'age_years' => 2,
            'age_months' => 6,
            'size' => 'середній',
            'size_en' => 'medium',
            'additional_information' => $this->faker->sentence(),
            'additional_information_en' => $this->faker->sentence(),
        ];
    }
} 
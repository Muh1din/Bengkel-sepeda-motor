<?php

namespace Database\Factories;

use App\Models\MaterialCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialCategoryFactory extends Factory
{
    protected $model = MaterialCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
        ];
    }
}

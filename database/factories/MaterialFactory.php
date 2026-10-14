<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'material_category_id' => MaterialCategory::factory(),
            'name' => fake()->words(3, true),
            'barcode' => fake()->unique()->numerify('899##########'),
            'unit' => 'pcs',
            'stock' => 0,
            'minimum_stock' => 5,
            'description' => fake()->sentence(),
        ];
    }
}

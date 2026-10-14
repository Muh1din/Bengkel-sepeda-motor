<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialRequestItemFactory extends Factory
{
    protected $model = MaterialRequestItem::class;

    public function definition(): array
    {
        return [
            'material_request_id' => MaterialRequest::factory(),
            'material_id' => Material::factory(),
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}

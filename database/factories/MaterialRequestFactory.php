<?php

namespace Database\Factories;

use App\Models\MaterialRequest;
use App\Models\WorkOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialRequestFactory extends Factory
{
    protected $model = MaterialRequest::class;

    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'mechanic_id' => User::factory(),
            'status' => 'PENDING',
            'notes' => fake()->sentence(),
        ];
    }
}

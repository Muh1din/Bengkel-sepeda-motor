<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'mechanic_id' => User::factory()->mechanic(),
            'work_order_code' => fake()->unique()->bothify('WO-######'),
            'received_at' => now(),
            'status' => 'IN_PROGRESS',
            'notes' => fake()->sentence(),
            'completed_at' => null,
        ];
    }
}

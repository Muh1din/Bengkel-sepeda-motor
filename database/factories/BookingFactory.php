<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'booking_code' => fake()->unique()->bothify('BK-######'),
            'customer_id' => null,
            'vehicle_id' => null,
            'service_type' => fake()->randomElement([
                'cat body',
                'service berkala',
                'ganti oli',
                'ganti kampas rem',
                'servis mesin',
            ]),
            'booking_date' => fake()
                ->dateTimeBetween('now', '+30 days')
                ->format('Y-m-d'),
            'booking_time' => fake()->time(),
            'complaint' => fake()->randomElement([
                'cat motor kusam',
                'tarikan motor terasa berat',
                'rem belakang ngelos',
                'mesin ngebul',
            ]),
            'status' => 'PENDING',
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Booking $booking) {
            $customer = Customer::factory()->create();

            $vehicle = Vehicle::factory()->create([
                'customer_id' => $customer->id,
            ]);

            $booking->customer_id = $customer->id;
            $booking->vehicle_id = $vehicle->id;
        });
    }

    public function confirmed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'CONFIRMED',
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'COMPLETED',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'REJECTED',
        ]);
    }
}

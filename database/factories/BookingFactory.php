<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_code' => fake()->unique()->bothify('BK-######'),
            'service_type' => fake()->randomElement([
                'cat body',
                'service berkala',
                'ganti oli',
                'ganti kampas rem',
                'servis mesin'
            ]),
            'booking_date' => fake()->dateTimeBetween('now', '+ 30 days')->format('Y-m-d'),
            'booking_time' => fake()->time(),
            'complaint' => fake()->randomElement([
                'cat motor kusam',
                'tarikan motor terasa berat',
                'rem belakang ngelos',
                'mesin ngebul'
            ]),
            'status' => 'PENDING',
        ];
    }

    public function confirmed(): static 
    {
        return $this->state(fn (array $attrbutes) => [
            'status' => 'CONFIRMED'
        ]);
    }

    public function inProgress(): static 
    {
        return $this->state(fn (array $attrbutes) => [
            'status' => 'IN_PROGRESS'
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attrbutes) => [
            'status' => 'COMPLETED'
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attrbutes) => [
            'status' => 'REJECTED'
        ]);
    }
}

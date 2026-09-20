<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'license_plate' => fake()->unique()->bothify('B #### ???'),

            'brand' => fake()->randomElement([
                'Honda',
                'Yamaha',
                'Suzuki',
                'Kawasaki',
            ]),

            'model' => fake()->randomElement([
                'Vario 160',
                'NMAX',
                'Beat',
                'Scoopy',
                'Aerox',
            ]),

            'year' => fake()->numberBetween(2018, 2026),

            'color' => fake()->safeColorName(),
        ];
    }
}

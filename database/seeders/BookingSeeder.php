<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::with('customer')->get();

        foreach($vehicles as $vehicle){
            Booking::factory()->create([
                'customer_id' => $vehicle->customer_id,
                'vehicle_id' => $vehicle->id
            ]);
        }
    }
}

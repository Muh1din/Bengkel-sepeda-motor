<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();



        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SettingSeeder::class
        ]);

        // $customer = Customer::factory()->create();

        // Vehicle::factory()
        //     ->count(3)
        //     ->for($customer)
        //     ->create();
    }
}

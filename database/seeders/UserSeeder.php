<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = Role::where('name', 'Owner')->first();
        $service_advisor = Role::where('name', 'ServiceAdvisor')->first();
        $mechanic = Role::where('name', 'Mechanic')->first();
        $admin = Role::where('name', 'Admin')->first();


        User::create([
            'name' => 'Eka',
            'email' => 'eka@gmail.com',
            'phone' => '081234567890',
            'password' => 'rahasia',
            'role_id' => $owner->id
        ]);

        User::create([
            'name' => 'Dian',
            'email' => 'dian@gmail.com',
            'phone' => '083812345678',
            'password' => 'rahasia',
            'role_id' => $service_advisor->id
        ]);

        User::create([
            'name' => 'Rafli',
            'email' => 'rafli@gmail.com',
            'phone' => '083812349876',
            'password' => 'rahasia',
            'role_id' => $mechanic->id
        ]);

        User::create([
            'name' => 'Ibnu',
            'email' => 'ibnu@gmail.com',
            'phone' => '083898765432',
            'password' => 'rahasia',
            'role_id' => $admin->id
        ]);
    }
}

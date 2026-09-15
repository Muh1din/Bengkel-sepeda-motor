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

        User::create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'phone' => '081234567890',
            'password' => 'rahasia',
            'role_id' => $owner->id
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat User (dengan username dan ULID akan auto-generate)
        User::firstOrCreate(
            ['email' => 'testexample@gmail.com'],
            [
                'name' => 'Test User',
                'username' => 'testing',
                'email' => 'testexample@gmail.com',
                'password' => Hash::make('password123')
            ]
        );

        // Membuat Customer (tanpa username karena kolom tidak ada)
        Customer::firstOrCreate(
            ['email' => 'admin@jump.net'],
            [
                'name' => 'Admin',
                'email' => 'admin@jump.net',
                'password' => Hash::make('password')
            ]
        );
    }
}

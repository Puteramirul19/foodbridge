<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@foodbridge.com',
            'phone_number' => '1234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create sample donor
        DB::table('users')->insert([
            'name' => 'John Donor',
            'email' => 'donor@example.com',
            'phone_number' => '9876543210',
            'role' => 'donor',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create sample recipient
        DB::table('users')->insert([
            'name' => 'Jane Recipient',
            'email' => 'recipient@example.com',
            'phone_number' => '5555555555',
            'role' => 'recipient',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
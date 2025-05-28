<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'lastname' => 'User',
            'phone_number' => '+380501234567',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'isAdmin' => true,
            'email_verified_at' => now()
        ]);
    }
} 
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User - only if not exists
        \App\Models\User::updateOrCreate(
            ['email' => 'faculty@gmail.com'],
            [
                'name' => 'Faculty Head',
                'password' => Hash::make('faculty123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
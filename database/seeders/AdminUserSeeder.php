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
        \App\Models\User::updateOrCreate(
            ['email' => env('FACULTY_EMAIL', 'davepalola16@gmail.com')],
            [
                'name' => env('FACULTY_NAME', 'Faculty Head'),
                'password' => Hash::make(env('FACULTY_PASSWORD', 'password123')),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
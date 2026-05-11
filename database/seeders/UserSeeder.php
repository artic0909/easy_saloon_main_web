<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@easysaloon.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'username' => 'admin',
            'is_active' => true,
        ]);

        // Staff
        User::create([
            'name' => 'Professional Staff',
            'email' => 'staff@easysaloon.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'username' => 'staff',
            'is_active' => true,
        ]);

        // User
        User::create([
            'name' => 'Saklin Mustak',
            'email' => 'user@easysaloon.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'username' => 'saklin',
            'is_active' => true,
        ]);
    }
}

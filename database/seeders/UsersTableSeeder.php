<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Membuat Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('superadminpassword'), // Ganti dengan password yang aman
            'role' => 'super-admin',
        ]);

        // Membuat Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminpassword'), // Ganti dengan password yang aman
            'role' => 'admin',
        ]);

        // Membuat User Biasa
        User::create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('userpassword'), // Ganti dengan password yang aman
            'role' => 'user',
        ]);
    }
}

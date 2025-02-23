<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

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

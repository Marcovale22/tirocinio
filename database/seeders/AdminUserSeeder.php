<?php

namespace Database\Seeders;

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
        User::create([
            'name' => 'Admin User',
            'username' => 'adminadmin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12341234'),
            'tipo_utente' => 'persona',
            'ruolo' => 'admin',
        ]);

        // Staff
        User::create([
            'name' => 'Staff User',
            'username' => 'staffstaff',
            'email' => 'staff@example.com',
            'password' => Hash::make('12341234'),
            'tipo_utente' => 'persona',
            'ruolo' => 'staff',
        ]);

        // Utente normale
        User::create([
            'name' => 'User Simple',
            'username' => 'utenteutente',
            'email' => 'utente@example.com',
            'password' => Hash::make('12341234'),
            'tipo_utente' => 'persona',
            'ruolo' => 'utente',
        ]);
    }
}

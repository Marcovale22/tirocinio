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
        User::firstOrCreate(
            ['email' => 'admin@vinicola.test'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('12341234'),
                'tipo_utente' => 'persona',
                'ruolo' => 'admin',
            ]
        );
    }
}

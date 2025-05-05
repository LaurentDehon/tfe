<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de 15 users
        for ($i = 1; $i <= 15; $i++) {
            User::create([
                'name' => 'user' . sprintf('%02d', $i),
                'email' => 'user' . sprintf('%02d', $i) . '@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]);
        }
    }
}

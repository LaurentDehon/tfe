<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création d'un utilisateur admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tfe.com',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);
        
        // Exécution des seeders personnalisés
        $this->call([
            UserSeeder::class,
            CourseSeeder::class,
            ToolSeeder::class,
            ConceptSeeder::class,
            MilestoneSeeder::class,
        ]);
    }
}

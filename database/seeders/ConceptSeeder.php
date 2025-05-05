<?php

namespace Database\Seeders;

use App\Models\Concept;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de 15 concepts
        for ($i = 1; $i <= 15; $i++) {
            Concept::create([
            'name' => 'concept' . sprintf('%02d', $i),
            ]);
        }
    }
}

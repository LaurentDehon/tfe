<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de 15 tools
        for ($i = 1; $i <= 15; $i++) {
            Tool::create([
                'name' => 'tool' . sprintf('%02d', $i),
            ]);
        }
    }
}

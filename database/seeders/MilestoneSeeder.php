<?php

namespace Database\Seeders;

use App\Models\Milestone;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $milestones = [
            [
                'title' => 'Choix du sujet',
                'description' => 'Définir le sujet de votre TFE en accord avec votre promoteur. Cette étape est cruciale car elle oriente tout le travail à venir.',
                'position' => 1,
            ]
        ];
        
        foreach ($milestones as $milestone) {
            Milestone::create($milestone);
        }
    }
}

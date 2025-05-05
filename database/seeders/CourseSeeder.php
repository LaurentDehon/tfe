<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de 15 courses
        for ($i = 1; $i <= 15; $i++) {
            Course::create([
                'name' => 'course' . sprintf('%02d', $i),
            ]);
        }
    }
}

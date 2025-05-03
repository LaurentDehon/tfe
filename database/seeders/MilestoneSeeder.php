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
                'tools' => 'Google Scholar, Bibliothèque universitaire',
                'concepts' => 'Domaine de recherche, Problématique, Originalité',
                'courses' => 'Méthodologie de recherche, Introduction à la recherche scientifique',
                'position' => 1,
            ],
            [
                'title' => 'Recherche bibliographique',
                'description' => 'Explorer les publications existantes sur le sujet choisi pour constituer une base de connaissances solide.',
                'tools' => 'Zotero, Mendeley, Google Scholar, ResearchGate',
                'concepts' => 'Synthèse, Analyse critique, État de l\'art',
                'courses' => 'Méthodologie de recherche, Documentation scientifique',
                'position' => 2,
            ],
            [
                'title' => 'Rédaction du plan',
                'description' => 'Structurer votre travail en chapitres et sections cohérents, en détaillant le contenu prévu pour chaque partie.',
                'tools' => 'Microsoft Word, Google Docs, MindMeister',
                'concepts' => 'Structure logique, Argumentation, Progression des idées',
                'courses' => 'Expression écrite, Méthodologie du travail scientifique',
                'position' => 3,
            ],
            [
                'title' => 'Développement du prototype',
                'description' => 'Mettre en place une solution technique ou pratique qui répond à la problématique identifiée.',
                'tools' => 'GitHub, Visual Studio Code, Frameworks divers selon le projet',
                'concepts' => 'Conception, Développement, Tests, Validation',
                'courses' => 'Génie logiciel, Programmation avancée, Design Patterns',
                'position' => 4,
            ],
            [
                'title' => 'Rédaction du mémoire',
                'description' => 'Rédiger le document final qui présente votre travail de recherche et vos résultats de manière claire et structurée.',
                'tools' => 'Microsoft Word, LaTeX, Grammarly',
                'concepts' => 'Rédaction scientifique, Illustration des résultats, Argumentation',
                'courses' => 'Communication scientifique, Méthodologie de rédaction',
                'position' => 5,
            ],
            [
                'title' => 'Préparation de la défense',
                'description' => 'Préparer une présentation orale qui met en valeur les points forts de votre TFE et anticipe les questions du jury.',
                'tools' => 'PowerPoint, Google Slides, Prezi',
                'concepts' => 'Communication orale, Synthèse, Argumentation',
                'courses' => 'Techniques de présentation, Communication scientifique',
                'position' => 6,
            ],
        ];
        
        foreach ($milestones as $milestone) {
            Milestone::create($milestone);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Commentaires principaux
        $comments = [
            [
                'author_name' => 'Marie Dupont',
                'content' => 'J\'ai du mal à trouver un sujet original pour mon TFE. Quelqu\'un aurait-il des conseils sur comment trouver une idée vraiment innovante ?',
                'votes_up' => 5,
                'votes_down' => 0,
                'created_at' => Carbon::now()->subDays(30),
            ],
            [
                'author_name' => 'Thomas Legrand',
                'content' => 'Est-ce que quelqu\'un pourrait me recommander un bon logiciel pour la gestion des références bibliographiques ? J\'hésite entre Zotero et Mendeley.',
                'votes_up' => 8,
                'votes_down' => 1,
                'created_at' => Carbon::now()->subDays(25),
            ],
            [
                'author_name' => 'Sophie Martin',
                'content' => 'J\'ai fini la rédaction de mon mémoire mais j\'ai du mal à préparer ma défense orale. Des conseils pour gérer le stress et bien structurer ma présentation ?',
                'votes_up' => 12,
                'votes_down' => 0,
                'created_at' => Carbon::now()->subDays(15),
            ],
        ];

        // Création des commentaires principaux
        foreach ($comments as $commentData) {
            Comment::create($commentData);
        }

        // Ajout de réponses aux commentaires
        $replies = [
            // Réponses au premier commentaire
            [
                'author_name' => 'Lucas Petit',
                'content' => 'Je te conseille de discuter avec des professionnels du domaine qui pourront t\'orienter vers des problématiques concrètes et actuelles.',
                'parent_id' => 1,
                'votes_up' => 3,
                'votes_down' => 0,
                'created_at' => Carbon::now()->subDays(29),
            ],
            [
                'author_name' => 'Emma Dubois',
                'content' => 'Essaie de lire des publications récentes dans ton domaine et de voir quelles sont les questions qui restent sans réponse ou qui méritent d\'être approfondies.',
                'parent_id' => 1,
                'votes_up' => 4,
                'votes_down' => 0,
                'created_at' => Carbon::now()->subDays(28),
            ],
            
            // Réponses au deuxième commentaire
            [
                'author_name' => 'Léa Bernard',
                'content' => 'Personnellement, j\'utilise Zotero et j\'en suis très satisfaite. L\'extension pour le navigateur est vraiment pratique pour sauvegarder directement les références depuis les sites web.',
                'parent_id' => 2,
                'votes_up' => 6,
                'votes_down' => 0,
                'created_at' => Carbon::now()->subDays(24),
            ],
            [
                'author_name' => 'Hugo Moreau',
                'content' => 'J\'ai essayé les deux et je préfère Mendeley pour son interface plus intuitive et sa meilleure intégration avec Word.',
                'parent_id' => 2,
                'votes_up' => 2,
                'votes_down' => 1,
                'created_at' => Carbon::now()->subDays(23),
            ],
            
            // Réponses au troisième commentaire
            [
                'author_name' => 'Alice Fournier',
                'content' => 'Pour gérer le stress, je te conseille de faire plusieurs répétitions devant des amis ou de la famille. Concernant la structure, commence par une accroche forte, puis présente ta problématique, ta méthodologie, tes résultats principaux et termine par les perspectives.',
                'parent_id' => 3,
                'votes_up' => 7,
                'votes_down' => 0,
                'created_at' => Carbon::now()->subDays(14),
            ],
            [
                'author_name' => 'Nicolas Roux',
                'content' => 'N\'oublie pas de préparer quelques slides de secours pour répondre aux questions potentielles du jury. Ça donne confiance et montre que tu as bien anticipé les interrogations.',
                'parent_id' => 3,
                'votes_up' => 5,
                'votes_down' => 0,
                'created_at' => Carbon::now()->subDays(12),
            ],
        ];
        
        // Création des réponses
        foreach ($replies as $replyData) {
            Comment::create($replyData);
        }
    }
}

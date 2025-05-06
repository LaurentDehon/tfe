<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;

class CommentsList extends Component
{
    public $comments;
    public $newComment = [
        'author_name' => '',
        'content' => '',
        'parent_id' => null
    ];
    
    // Ajout de l'écouteur pour l'événement commentDeleted
    protected $listeners = [
        'commentReplied' => 'refreshComments',
        'commentDeleted' => 'refreshComments'
    ];

    public function mount()
    {
        $this->refreshComments();
    }
    
    public function refreshComments()
    {
        // On récupère uniquement les commentaires de premier niveau
        $this->comments = Comment::whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->with('replies')
            ->get();
    }

    public function addComment()
    {
        $this->validate([
            'newComment.author_name' => 'required|min:2|max:50',
            'newComment.content' => 'required|min:5'
        ]);

        Comment::create([
            'author_name' => $this->newComment['author_name'],
            'content' => $this->newComment['content'],
            'parent_id' => $this->newComment['parent_id'],
        ]);

        // Réinitialisation du formulaire
        $this->newComment = [
            'author_name' => $this->newComment['author_name'], // On conserve le nom pour faciliter l'ajout multiple
            'content' => '',
            'parent_id' => null
        ];

        $this->refreshComments();
        
        $this->dispatch('commentAdded');
    }

    public function render()
    {
        return view('livewire.comments-list');
    }
}

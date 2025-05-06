<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CommentItem extends Component
{
    public Comment $comment;
    public $showReplyForm = false;
    public $replyContent = '';
    public $replyAuthorName = '';
    
    // Ajouter les écouteurs pour les événements de suppression
    protected $listeners = [
        'comment:delete' => 'handleCommentDelete',
        'reply:delete' => 'handleReplyDelete'
    ];

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function toggleReplyForm()
    {
        $this->showReplyForm = !$this->showReplyForm;
    }

    public function addReply()
    {
        $this->validate([
            'replyAuthorName' => 'required|min:2|max:50',
            'replyContent' => 'required|min:5',
        ]);

        Comment::create([
            'author_name' => $this->replyAuthorName,
            'content' => $this->replyContent,
            'parent_id' => $this->comment->id,
        ]);

        $this->replyContent = '';
        $this->showReplyForm = false;
        
        // Rafraîchir le commentaire pour afficher la nouvelle réponse
        $this->comment = Comment::with('replies')->find($this->comment->id);
        
        // Émettre un événement pour informer le composant parent
        $this->dispatch('commentReplied');
    }

    public function voteUp()
    {
        $this->comment->increment('votes_up');
        $this->comment->refresh();
    }

    public function voteDown()
    {
        $this->comment->increment('votes_down');
        $this->comment->refresh();
    }

    // Gestionnaire pour la suppression d'un commentaire principal
    public function handleCommentDelete($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            $this->dispatch('notify', type: 'error', title: 'Action non autorisée', message: 'Vous n\'avez pas le droit de supprimer ce commentaire.');
            return;
        }

        // Vérifier que l'ID correspond au commentaire actuel
        if ($id == $this->comment->id) {
            // Supprimer d'abord toutes les réponses
            $this->comment->replies()->delete();
            
            // Puis supprimer le commentaire lui-même
            $this->comment->delete();
            
            // Informer le parent que le commentaire a été supprimé
            $this->dispatch('commentDeleted');
            
            $this->dispatch('notify', type: 'success', title: 'Suppression réussie', message: 'Le commentaire a été supprimé avec succès.');
        }
    }

    // Gestionnaire pour la suppression d'une réponse
    public function handleReplyDelete($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            $this->dispatch('notify', type: 'error', title: 'Action non autorisée', message: 'Vous n\'avez pas le droit de supprimer cette réponse.');
            return;
        }

        // Chercher la réponse parmi les réponses du commentaire actuel
        $reply = $this->comment->replies()->find($id);
        
        if ($reply) {
            $reply->delete();
            
            // Rafraîchir le commentaire pour refléter la suppression de la réponse
            $this->comment = Comment::with('replies')->find($this->comment->id);
            
            $this->dispatch('notify', type: 'success', title: 'Suppression réussie', message: 'La réponse a été supprimée avec succès.');
        }
    }

    public function render()
    {
        return view('livewire.comment-item', [
            'isAdmin' => Auth::check() && Auth::user()->is_admin
        ]);
    }
}

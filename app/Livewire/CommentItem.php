<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CommentItem extends Component
{
    public Comment $comment;
    public $showReplyForm = false;
    public $replyContent = '';
    public $replyAuthorName = '';
    public $userVote = null; // Pour suivre le vote de l'utilisateur actuel
    public $replyVotes = []; // Pour stocker les votes de l'utilisateur sur les réponses
    
    // Ajouter les écouteurs pour les événements de suppression
    protected $listeners = [
        'comment:delete' => 'handleCommentDelete',
        'reply:delete' => 'handleReplyDelete'
    ];

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
        
        // Initialiser le nom de l'auteur de réponse avec le nom de l'utilisateur connecté
        if (Auth::check()) {
            $this->replyAuthorName = Auth::user()->name;
        }
        
        // Récupérer le vote de l'utilisateur depuis le cookie pour le commentaire principal
        $this->userVote = Cookie::get('comment_vote_' . $this->comment->id);
        
        // Récupérer les votes de l'utilisateur pour chaque réponse
        foreach ($this->comment->replies as $reply) {
            $this->replyVotes[$reply->id] = Cookie::get('comment_vote_' . $reply->id);
        }
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
        // Récupérer le vote actuel de l'utilisateur
        $currentVote = Cookie::get('comment_vote_' . $this->comment->id);
        
        if ($currentVote === 'up') {
            // Si l'utilisateur a déjà voté "up", annuler son vote
            $this->comment->decrement('votes_up');
            Cookie::queue(Cookie::forget('comment_vote_' . $this->comment->id));
            $this->userVote = null;
        } else {
            // Si l'utilisateur n'a pas encore voté ou a voté "down"
            if ($currentVote === 'down') {
                // Annuler le vote "down" s'il existe
                $this->comment->decrement('votes_down');
            }
            
            // Ajouter un vote "up"
            $this->comment->increment('votes_up');
            Cookie::queue('comment_vote_' . $this->comment->id, 'up', 60 * 24 * 30); // Cookie valable 30 jours
            $this->userVote = 'up';
        }
        
        $this->comment->refresh();
    }

    public function voteDown()
    {
        // Récupérer le vote actuel de l'utilisateur
        $currentVote = Cookie::get('comment_vote_' . $this->comment->id);
        
        if ($currentVote === 'down') {
            // Si l'utilisateur a déjà voté "down", annuler son vote
            $this->comment->decrement('votes_down');
            Cookie::queue(Cookie::forget('comment_vote_' . $this->comment->id));
            $this->userVote = null;
        } else {
            // Si l'utilisateur n'a pas encore voté ou a voté "up"
            if ($currentVote === 'up') {
                // Annuler le vote "up" s'il existe
                $this->comment->decrement('votes_up');
            }
            
            // Ajouter un vote "down"
            $this->comment->increment('votes_down');
            Cookie::queue('comment_vote_' . $this->comment->id, 'down', 60 * 24 * 30); // Cookie valable 30 jours
            $this->userVote = 'down';
        }
        
        $this->comment->refresh();
    }
    
    public function voteReplyUp($replyId)
    {
        $reply = Comment::find($replyId);
        if (!$reply) {
            return;
        }
        
        // Récupérer le vote actuel de l'utilisateur
        $currentVote = Cookie::get('comment_vote_' . $replyId);
        
        if ($currentVote === 'up') {
            // Si l'utilisateur a déjà voté "up", annuler son vote
            $reply->decrement('votes_up');
            Cookie::queue(Cookie::forget('comment_vote_' . $replyId));
            $this->replyVotes[$replyId] = null;
        } else {
            // Si l'utilisateur n'a pas encore voté ou a voté "down"
            if ($currentVote === 'down') {
                // Annuler le vote "down" s'il existe
                $reply->decrement('votes_down');
            }
            
            // Ajouter un vote "up"
            $reply->increment('votes_up');
            Cookie::queue('comment_vote_' . $replyId, 'up', 60 * 24 * 30); // Cookie valable 30 jours
            $this->replyVotes[$replyId] = 'up';
        }
        
        // Rafraîchir la réponse pour obtenir les nouveaux décomptes de votes
        $reply->refresh();
        
        // Mettre à jour la réponse dans notre collection de commentaires
        $this->comment->refresh();
    }
    
    public function voteReplyDown($replyId)
    {
        $reply = Comment::find($replyId);
        if (!$reply) {
            return;
        }
        
        // Récupérer le vote actuel de l'utilisateur
        $currentVote = Cookie::get('comment_vote_' . $replyId);
        
        if ($currentVote === 'down') {
            // Si l'utilisateur a déjà voté "down", annuler son vote
            $reply->decrement('votes_down');
            Cookie::queue(Cookie::forget('comment_vote_' . $replyId));
            $this->replyVotes[$replyId] = null;
        } else {
            // Si l'utilisateur n'a pas encore voté ou a voté "up"
            if ($currentVote === 'up') {
                // Annuler le vote "up" s'il existe
                $reply->decrement('votes_up');
            }
            
            // Ajouter un vote "down"
            $reply->increment('votes_down');
            Cookie::queue('comment_vote_' . $replyId, 'down', 60 * 24 * 30); // Cookie valable 30 jours
            $this->replyVotes[$replyId] = 'down';
        }
        
        // Rafraîchir la réponse pour obtenir les nouveaux décomptes de votes
        $reply->refresh();
        
        // Mettre à jour la réponse dans notre collection de commentaires
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

<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;

class CommentItem extends Component
{
    public Comment $comment;
    public $showReplyForm = false;
    public $replyContent = '';
    public $replyAuthorName = '';

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

    public function render()
    {
        return view('livewire.comment-item');
    }
}

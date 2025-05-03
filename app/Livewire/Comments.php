<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Comments extends Component
{
    public $comments;
    public $author = '';
    public $content = '';
    public $parentId = null;
    public $replyTo = null;

    public function mount()
    {
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = Comment::orderBy('created_at')->get()->groupBy('parent_id');
    }

    public function postComment()
    {
        $this->validate([
            'author' => 'required',
            'content' => 'required',
        ]);
        Comment::create([
            'author' => $this->author,
            'content' => $this->content,
            'parent_id' => $this->replyTo,
        ]);
        $this->author = '';
        $this->content = '';
        $this->replyTo = null;
        $this->loadComments();
    }

    public function setReply($id)
    {
        $this->replyTo = $id;
    }

    public function cancelReply()
    {
        $this->replyTo = null;
    }

    public function vote($id, $type)
    {
        $comment = Comment::find($id);
        if ($type === 'up') {
            $comment->increment('votes_up');
        } elseif ($type === 'down') {
            $comment->increment('votes_down');
        }
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.comments');
    }
}

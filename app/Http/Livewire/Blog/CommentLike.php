<?php

namespace App\Http\Livewire\Blog;

use App\Models\Comment;
use Livewire\Component;

class CommentLike extends Component
{
    public Comment $comment;
    public int $count;
    public $user;
    public bool $liked;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
        $this->user = auth()->user();
        $this->count = $this->comment->likers()->count();
        $this->liked = $this->user ? $this->comment->isLikedBy($this->user) : false;
    }

    public function like()
    {
        // Redirect guest to login page
        if (auth()->guest()) {
            return redirect()->route('login');
        }
        // else if user; like
        $this->user->toggleLike($this->comment);
        $this->count = $this->comment->likers()->count();
        $this->liked = $this->comment->isLikedBy($this->user);
    }

    public function render()
    {
        return view('livewire.blog.comment-like');
    }
}

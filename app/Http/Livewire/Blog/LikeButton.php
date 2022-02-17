<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class LikeButton extends Component
{
    public Post $post;
    public int $count;
    public $user;
    public bool $liked;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->user = auth()->user();
        $this->count = $this->post->likers()->count();
        $this->liked = $this->user ? $this->post->isLikedBy($this->user) : false;
    }

    public function like()
    {
        // Redirect guest to login page
        if (auth()->guest()) {
            return redirect()->route('login');
        }
        // else if user; like
        $this->user->toggleLike($this->post);
        $this->count = $this->post->likers()->count();
        $this->liked = $this->post->isLikedBy($this->user);
    }

    public function render()
    {
        return view('livewire.blog.like-button');
    }
}

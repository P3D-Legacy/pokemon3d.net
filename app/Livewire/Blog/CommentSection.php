<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class CommentSection extends Component
{
    public int|Post $post;

    public $comments;

    protected $listeners = [
        'commentAdded' => '$refresh',
    ];

    public function mount(int|Post $post)
    {
        if ($post instanceof Post) {
            $this->post = $post;
        } else {
            $this->post = Post::find($post);
        }
        $this->comments = $this->post->comments->sortByDesc('created_at');
    }

    public function render()
    {
        return view('livewire.blog.comment-section');
    }
}

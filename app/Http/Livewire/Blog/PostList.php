<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class PostList extends Component
{
    public $posts;

    protected $listeners = [
        'postUpdated' => 'update',
    ];

    public function mount()
    {
        $this->posts = Post::all();
    }

    public function update()
    {
        $this->posts = Post::all();
    }

    public function render()
    {
        return view('livewire.blog.post-list');
    }
}

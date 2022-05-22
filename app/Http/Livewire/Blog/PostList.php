<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    protected $listeners = [
        'postUpdated' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.blog.post-list', [
            'posts' => Post::orderBy('published_at', 'desc')->paginate(),
        ]);
    }
}

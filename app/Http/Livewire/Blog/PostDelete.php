<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use LivewireUI\Modal\ModalComponent;

class PostDelete extends ModalComponent
{
    public $post_id;

    public $post;

    public function mount()
    {
        $this->post = Post::find($this->post_id);
    }

    public function delete()
    {
        $this->post->delete();
        $this->closeModal();
        $this->emit('postUpdated');
    }

    public function render()
    {
        return view('livewire.blog.post-delete');
    }
}

<?php

namespace App\Http\Livewire\Blog;

use AliBayat\LaravelCategorizable\Category;
use App\Models\Post;
use App\Models\Tag;
use LivewireUI\Modal\ModalComponent;

class PostForm extends ModalComponent
{
    public int|Post $post;
    public $tags;
    public array $checked;

    protected array $rules = [];

    public function mount(int|Post $post = null)
    {
        $this->post = $post ? Post::find($post) : new Post();
        $this->tags = Tag::all();
        $this->rules = $this->rules();
        $this->checked = $this->post->tags->pluck('name')->toArray() ?? [];
    }

    public function rules()
    {
        return [
            'post.title' => 'required|string|max:255|unique:posts,title,'.$this->post->id,
            'post.active' => ['required', 'integer'],
            'post.sticky' => ['required', 'integer'],
            'post.published_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'post.body' => ['required', 'string', 'min:25'],
            'checked' => ['required', 'array'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->post->user_id = auth()->id();
        $this->post->save();
        $this->post->syncTags($this->checked);

        $this->emit('postUpdated', $this->post->uuid);
        $this->closeModal();
    }
    public function render()
    {
        return view('livewire.blog.post-form');
    }
}

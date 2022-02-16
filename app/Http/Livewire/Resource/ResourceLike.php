<?php

namespace App\Http\Livewire\Resource;

use Livewire\Component;
use App\Models\Resource;

class ResourceLike extends Component
{
    public Resource $resource;
    public int $count;
    public $user;
    public bool $liked;

    public function mount(Resource $resource)
    {
        $this->resource = $resource;
        $this->user = auth()->user();
        $this->count = $this->resource->likers()->count();
        $this->liked = $this->user ? $this->resource->isLikedBy($this->user) : false;
    }

    public function like()
    {
        $this->user->toggleLike($this->resource);
        $this->count = $this->resource->likers()->count();
        $this->liked = $this->resource->isLikedBy($this->user);
    }

    public function render()
    {
        return view('livewire.resource.resource-like');
    }
}

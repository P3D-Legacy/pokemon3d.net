<?php

namespace App\Http\Livewire\Resource;

use App\Models\Resource;
use App\Notifications\Resource\LikeNotification;
use Livewire\Component;
use Notification;

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
        if($this->liked) {
            Notification::send($this->resource->user, new LikeNotification($this->resource, $this->user));
            $this->emit('notificationsUpdated');
        }
    }

    public function render()
    {
        return view('livewire.resource.resource-like');
    }
}

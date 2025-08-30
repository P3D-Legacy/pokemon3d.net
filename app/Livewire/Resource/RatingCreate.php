<?php

namespace App\Livewire\Resource;

use App\Models\Resource;
use Livewire\Component;

class RatingCreate extends Component
{
    public Resource $resource;

    public $body;

    public $rating;

    public $user;

    public $isSubmitting = false;

    public function mount(int|Resource $resource)
    {
        if (is_numeric($resource)) {
            $resource = Resource::find($resource);
        }
        $this->resource = $resource;
        $this->user = auth()->user();

        if (! $this->user) {
            abort(403, 'You must be logged in to rate resources.');
        }

        if ($this->user->id == $this->resource->user_id) {
            abort(403, 'You cannot rate your own resource.');
        }
    }

    public function save()
    {
        // Prevent duplicate submissions
        if ($this->isSubmitting) {
            return;
        }

        $this->isSubmitting = true;

        try {
            // Double check permissions
            if (! $this->user || $this->user->id == $this->resource->user_id) {
                abort(403);
            }

            $this->validate([
                'rating' => ['required', 'digits_between:1,5'],
                'body' => ['required', 'string', 'min:10', 'max:255'],
            ]);

            $this->resource->review($this->body, $this->user, $this->rating);

            session()->flash('flash.bannerStyle', 'success');
            session()->flash('flash.banner', __('Thank you for your review!'));

            return redirect()->route('resource.uuid', $this->resource->uuid);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Reset submission flag on validation error
            $this->isSubmitting = false;
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.resource.rating-create');
    }
}

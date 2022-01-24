<?php

namespace App\Http\Livewire\Resource;

use App\Models\Resource;
use LivewireUI\Modal\ModalComponent;

class RatingCreate extends ModalComponent
{
    public int|Resource $resource;
    public $body;
    public $rating;
    public $user;

    public function mount(int|Resource $resource)
    {
        $this->resource = $resource;
        $this->user = auth()->user();
    }

    public function save() {
        $this->validate([
            'rating' => [
                'digits_between:1,5',
            ],
            'body' => [
                'required',
                'string',
            ],
        ]);

        $resource = Resource::find($this->resource);
        
        $resource->rating([
            'title' => $this->user->username,
            'body' => $this->body,
            'customer_service_rating' => $this->rating,
            'quality_rating' => $this->rating,
            'friendly_rating' => $this->rating,
            'pricing_rating' => $this->rating,
            'rating' => $this->rating,
            'recommend' => 'Yes',
            'approved' => true, // This is optional and defaults to false
        ], $this->user);

        $this->emit('resourceUpdated', $resource->id);
        
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.resource.rating-create');
    }
}

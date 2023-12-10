<?php

namespace App\Http\Livewire\Review;

use Digikraaft\ReviewRating\Models\Review;
use Livewire\Component;

class ReviewList extends Component
{
    public $reviews;

    public $averageRating;

    public $numberOfReviews;

    protected $listeners = [
        'gameReviewed' => 'update',
    ];

    public function mount()
    {
        $this->reviews = Review::where('model_type', '=', \App\Models\GameVersion::class)
            ->orderBy('created_at', 'desc')
            ->get();
        $this->numberOfReviews = $this->reviews->count();
        $this->averageRating = $this->reviews->pluck('rating')->avg();
    }

    public function update()
    {
        $this->reviews = Review::where('model_type', '=', \App\Models\GameVersion::class)
            ->orderBy('created_at', 'desc')
            ->get();
        $this->numberOfReviews = $this->reviews->count();
        $this->averageRating = $this->reviews->pluck('rating')->avg();
    }

    public function render()
    {
        return view('livewire.review.review-list');
    }
}

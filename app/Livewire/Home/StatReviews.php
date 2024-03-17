<?php

namespace App\Livewire\Home;

use Digikraaft\ReviewRating\Models\Review;
use Livewire\Component;

class StatReviews extends Component
{
    public $numberOfReviews;

    public $averageRating;

    public function loadData()
    {
        $reviews = Review::where('model_type', \App\Models\GameVersion::class)
            ->orderBy('created_at', 'desc')
            ->get();
        $this->numberOfReviews = $reviews->count();
        $this->averageRating = round($reviews->pluck('rating')->avg(), 1);
    }

    public function render()
    {
        return view('livewire.home.stat-reviews');
    }
}

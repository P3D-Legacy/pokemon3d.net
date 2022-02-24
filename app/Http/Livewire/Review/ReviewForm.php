<?php

namespace App\Http\Livewire\Review;

use App\Models\GameVersion;
use LivewireUI\Modal\ModalComponent;

class ReviewForm extends ModalComponent
{
    public $body;
    public $rating;
    public $user;
    public $gameversion;
    public $gameversions;

    public function mount()
    {
        $this->user = auth()->user();
        $this->gameversions = GameVersion::orderBy('release_date', 'desc')
            ->take(3)
            ->get();
    }

    public function save()
    {
        $this->validate([
            'gameversion' => ['required'],
            'rating' => ['digits_between:1,5'],
            'body' => ['required', 'string', 'min:10', 'max:255'],
        ]);

        $gameversion = GameVersion::find($this->gameversion);
        $gameversion->review($this->body, $this->user, $this->rating);

        $this->emit('gameReviewed');

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.review.review-form');
    }
}

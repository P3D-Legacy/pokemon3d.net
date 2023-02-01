<?php

namespace App\Http\Livewire\Profile\GameSave;

use Livewire\Component;

class Details extends Component
{
    public $gamesave;

    public $details;

    public function mount($gamesave)
    {
        $this->gamesave = $gamesave;
        $this->details = [];
    }

    public function loadData()
    {
        $this->details = $this->gamesave->getPlayerDataDetails();
    }

    public function render()
    {
        return view('livewire.profile.game-save.details');
    }
}

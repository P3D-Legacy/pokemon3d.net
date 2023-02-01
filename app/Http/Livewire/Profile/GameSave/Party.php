<?php

namespace App\Http\Livewire\Profile\GameSave;

use Livewire\Component;

class Party extends Component
{
    public $party;

    public $gamesave;

    public function mount($gamesave)
    {
        $this->gamesave = $gamesave;
        $this->party = [];
    }

    public function loadData()
    {
        $this->party = $this->gamesave->getParty();
    }

    public function render()
    {
        return view('livewire.profile.game-save.party');
    }
}

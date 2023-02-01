<?php

namespace App\Http\Livewire\Profile\GameSave;

use Livewire\Component;

class Pokedex extends Component
{
    public $pokedex;

    public $gamesave;

    public function mount($gamesave)
    {
        $this->gamesave = $gamesave;
        $this->pokedex = [];
    }

    public function loadData()
    {
        $this->pokedex = $this->gamesave->getPokedex();
    }

    public function render()
    {
        return view('livewire.profile.game-save.pokedex');
    }
}

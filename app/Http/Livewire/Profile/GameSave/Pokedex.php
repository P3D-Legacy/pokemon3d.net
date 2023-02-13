<?php

namespace App\Http\Livewire\Profile\GameSave;

use Livewire\Component;

class Pokedex extends Component
{
    public $pokedex;

    public $gamesave;

    public $amountToLoad = 20;

    protected $listeners = [
        'loadMorePokedex' => 'loadMore',
    ];

    public function mount($gamesave)
    {
        $this->gamesave = $gamesave;
        $this->pokedex = [];
    }

    public function loadData()
    {
        $this->pokedex = array_slice($this->gamesave->getPokedex(), 0, $this->amountToLoad);
    }

    public function loadMore()
    {
        $this->amountToLoad += 15;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.profile.game-save.pokedex');
    }
}

<?php

namespace App\Http\Livewire\Profile\GameSave;

use Livewire\Component;

class Pokedex extends Component
{
    public $user_pokedex;

    public $gamesave;

    public $amountToLoad = 20;

    public $pokedexes;

    public $dex;

    protected $listeners = [
        'loadMorePokedex' => 'loadMore',
    ];

    public function mount($gamesave)
    {
        $this->gamesave = $gamesave;
        $this->user_pokedex = [];
        $this->pokedexes = [];
        $this->dex = [];
    }

    public function loadData()
    {
        //$this->user_pokedex = array_slice($this->gamesave->getPokedex(), 0, $this->amountToLoad);
        $this->pokedexes = \App\Models\Pokedex::all();
        // For each of the pokedexes get the pokemon ids and create an collection of the users pokedex
        foreach ($this->pokedexes as $pokedex) {
            $pokemon_ids = array_filter(explode(",", str_replace("[", "", str_replace("]", "", str_replace("\"", "", $pokedex->pokemon_ids)))));
            // Add the dex array to the pokedex collection
            $this->dex[$pokedex->slug]['entries'] = $this->gamesave->getPokedexByIds($pokemon_ids);
        }
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

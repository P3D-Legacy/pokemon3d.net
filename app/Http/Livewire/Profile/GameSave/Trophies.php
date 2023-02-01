<?php

namespace App\Http\Livewire\Profile\GameSave;

use Livewire\Component;

class Trophies extends Component
{
    public $trophies;

    public $gamejolt;

    public function mount($gamejolt)
    {
        $this->gamejolt = $gamejolt;
        $this->trophies = [];
    }

    public function loadData()
    {
        $this->trophies = $this->gamejolt->trophies;
    }

    public function render()
    {
        return view('livewire.profile.game-save.trophies');
    }
}

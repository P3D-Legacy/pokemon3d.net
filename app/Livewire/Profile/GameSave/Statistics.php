<?php

namespace App\Livewire\Profile\GameSave;

use Livewire\Component;

class Statistics extends Component
{
    public $gamesave;

    public $statistics;

    public function mount($gamesave)
    {
        $this->gamesave = $gamesave;
        $this->statistics = [];
    }

    public function loadData()
    {
        $this->statistics = $this->gamesave->getStatistics();
    }

    public function render()
    {
        return view('livewire.profile.game-save.statistics');
    }
}

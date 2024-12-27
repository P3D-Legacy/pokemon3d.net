<?php

namespace App\Livewire\Home;

use App\Helpers\StatsHelper;
use Livewire\Component;

class StatSeason extends Component
{
    public $inGameSeason;

    public function loadData()
    {
        $this->inGameSeason = ucfirst(StatsHelper::getInGameSeason());
    }

    public function render()
    {
        return view('livewire.home.stat-season');
    }
}

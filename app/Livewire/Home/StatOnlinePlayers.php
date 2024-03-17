<?php

namespace App\Livewire\Home;

use App\Helpers\NumberHelper;
use App\Helpers\StatsHelper;
use Livewire\Component;

class StatOnlinePlayers extends Component
{
    public $playersOnline;

    public function loadData()
    {
        $this->playersOnline = NumberHelper::nearestK(StatsHelper::countPlayers());
    }

    public function render()
    {
        return view('livewire.home.stat-online-players');
    }
}

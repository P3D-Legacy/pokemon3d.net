<?php

namespace App\Livewire\Home;

use App\Helpers\DiscordHelper;
use App\Helpers\NumberHelper;
use Livewire\Component;

class StatDiscordUsers extends Component
{
    public $discordUsers;

    public function loadData()
    {
        $this->discordUsers = NumberHelper::nearestK(DiscordHelper::countMembers());
    }

    public function render()
    {
        return view('livewire.home.stat-discord-users');
    }
}

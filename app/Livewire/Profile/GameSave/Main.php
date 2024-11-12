<?php

namespace App\Livewire\Profile\GameSave;

use Livewire\Component;

class Main extends Component
{
    public $user;

    public $gamesave;

    public $gamejolt;

    public function mount($user)
    {
        $this->user = $user;
        $this->gamesave = null;
        $this->gamejolt = null;
    }

    public function loadData()
    {
        $this->gamesave = $this->user->gamesave;
        $this->gamejolt = $this->user->gamejolt;
    }

    public function render()
    {
        return view('livewire.profile.game-save.main');
    }
}

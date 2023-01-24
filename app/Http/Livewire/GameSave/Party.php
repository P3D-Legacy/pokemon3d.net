<?php

namespace App\Http\Livewire\GameSave;

use App\Helpers\GameSaveHelper;
use Livewire\Component;

class Party extends Component
{
    protected $listeners = ['partyUpdated' => 'render'];

    public $party;

    public function mount()
    {
        $this->party = GameSaveHelper::getParty();
    }

    public function render()
    {
        return view('livewire.game-save.party');
    }
}

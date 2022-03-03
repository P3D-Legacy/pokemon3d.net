<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DiscordAccount extends Component
{
    public $username;

    public $discriminator;

    public $avatar;

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->discord ? $user->discord->username : null;
        $this->discriminator = $user->discord ? $user->discord->discriminator : null;
        $this->avatar = $user->discord ? $user->discord->avatar : null;
        $this->updated_at = $user->discord ? $user->discord->updated_at->diffForHumans() : null;
        $this->verified_at = $user->discord ? $user->discord->verified_at->diffForHumans() : null;
    }

    /**
     * Update the user's GameJolt Account credentials.
     *
     * @return void
     */
    public function remove()
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $user = Auth::user();

        if ($user->discord) {
            $user->discord->delete();
            $this->username = null;
            $this->discriminator = null;
            $this->avatar = null;
            $this->updated_at = null;
            $this->verified_at = null;
        }

        $this->emit('refresh');
    }

    public function render()
    {
        return view('livewire.profile.discord-account');
    }
}

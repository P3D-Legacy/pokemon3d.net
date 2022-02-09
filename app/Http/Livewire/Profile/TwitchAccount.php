<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TwitchAccount extends Component
{
    public $username;

    public $name;

    public $avatar;

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->twitch ? $user->twitch->username : null;
        $this->name = $user->twitch ? $user->twitch->name : null;
        $this->avatar = $user->twitch ? $user->twitch->avatar : null;
        $this->updated_at = $user->twitch
            ? $user->twitch->updated_at->diffForHumans()
            : null;
        $this->verified_at = $user->twitch
            ? $user->twitch->verified_at->diffForHumans()
            : null;
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

        if ($user->twitch) {
            $user->twitch->delete();
            $this->username = null;
            $this->name = null;
            $this->avatar = null;
            $this->updated_at = null;
            $this->verified_at = null;
        }

        $this->emit('refresh');
    }

    public function render()
    {
        return view('livewire.profile.twitch-account');
    }
}

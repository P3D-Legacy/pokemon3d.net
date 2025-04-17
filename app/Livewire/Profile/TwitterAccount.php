<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TwitterAccount extends Component
{
    public $username;

    public $name;

    public $avatar;

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->twitter ? $user->twitter->username : null;
        $this->name = $user->twitter ? $user->twitter->name : null;
        $this->avatar = $user->twitter ? $user->twitter->avatar : null;
        $this->updated_at = $user->twitter ? $user->twitter->updated_at->diffForHumans() : null;
        $this->verified_at = $user->twitter ? $user->twitter->verified_at->diffForHumans() : null;
    }

    /**
     * Update the user's Twitter Account credentials.
     */
    public function remove(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $user = Auth::user();

        if ($user->twitter) {
            $user->twitter->delete();
            $this->username = null;
            $this->name = null;
            $this->avatar = null;
            $this->updated_at = null;
            $this->verified_at = null;
        }

        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.profile.twitter-account');
    }
}

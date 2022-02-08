<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FacebookAccount extends Component
{
    public $name;
    public $avatar;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->facebook ? $user->facebook->name : null;
        $this->avatar = $user->facebook ? $user->facebook->avatar : null;
        $this->updated_at = $user->facebook
            ? $user->facebook->updated_at->diffForHumans()
            : null;
        $this->verified_at = $user->facebook
            ? $user->facebook->verified_at->diffForHumans()
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

        if ($user->facebook) {
            $user->facebook->delete();
            $this->name = null;
            $this->avatar = null;
            $this->updated_at = null;
            $this->verified_at = null;
        }

        $this->emit('refresh');

        return;
    }

    public function render()
    {
        return view('livewire.profile.facebook-account');
    }
}

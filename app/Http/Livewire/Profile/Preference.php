<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;

class Preference extends Component
{
    public $settings = [];

    public function mount()
    {
        $this->settings = auth()
            ->user()
            ->settings()
            ->all();
    }

    public function render()
    {
        return view('livewire.profile.preference');
    }

    public function toggle($setting)
    {
        $user = auth()->user();
        $user->settings()->set($setting, ! $user->settings()->get($setting));
        $this->settings = auth()
            ->user()
            ->settings()
            ->all();

        return $user->settings()->get($setting);
    }
}

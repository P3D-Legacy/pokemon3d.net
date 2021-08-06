<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class GameJoltAccount extends Component
{
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'gamejolt_username' => '',
        'gamejolt_token' => '',
    ];

    public $consents = [];

    public function mount() {
        $this->state['gamejolt_username'] = Auth::user()->gamejolt_username;
        $this->state['gamejolt_token'] = Auth::user()->gamejolt_token;
    }

    /**
     * Update the user's GameJolt Account credentials.
     *
     * @return void
     */
    public function updateGameJoltAccount(Request $request)
    {
        $this->resetErrorBag();

        Auth::user()->update($request->all());

        $this->emit('saved');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile.game-jolt-account');
    }
}

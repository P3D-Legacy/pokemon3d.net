<?php

namespace App\Http\Livewire\Profile;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Harrk\GameJoltApi\GamejoltApi;
use Illuminate\Support\Facades\Auth;
use Harrk\GameJoltApi\GamejoltConfig;
use Harrk\GameJoltApi\Exceptions\TimeOutException;

class GameJoltAccount extends Component
{
    public $gamejolt_username;
    public $gamejolt_token;
    public $gamejolt_updated_at;
    public $gamejolt_verified_at;

    public function mount() {
        $user = Auth::user();
        $this->gamejolt_username = $user->gamejolt_username;
        $this->gamejolt_token = $user->gamejolt_token;
        $this->gamejolt_updated_at =  ($user->gamejolt_updated_at ? $user->gamejolt_updated_at->diffForHumans() : null);
        $this->gamejolt_verified_at =  ($user->gamejolt_verified_at ? $user->gamejolt_verified_at->diffForHumans() : null);
    }

    /**
     * Update the user's GameJolt Account credentials.
     *
     * @return void
     */
    public function save()
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $user = Auth::user();

        $this->validate([
            'gamejolt_username' => [
                'required',
                'alpha_dash',
                'max:30',
                'min:4',
                Rule::unique('users')->ignore($user->id),
            ],
            'gamejolt_token' => [
                'required',
                'alpha_dash',
                'max:30',
                'min:4',
            ],
        ]);

        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        
        try {
            $auth = $api->users()->auth($this->gamejolt_username, $this->gamejolt_token);
        } catch (TimeOutException $e) {
            $this->addError('error', $e->getMessage());
            return false; // Stop here
        }
        
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
            $error = $auth['response']['message'];
            // Better description of username/token error
            if($error == "No such user with the credentials passed in could be found.") {
                $error = "Username and/or token is wrong.";
            }
            $this->addError('error', $error);
            return false; // Stop here
        }
        
        $data = [
            'gamejolt_username' => $this->gamejolt_username,
            'gamejolt_token' => $this->gamejolt_token,
            'gamejolt_updated_at' => Carbon::now()->toDateTimeString(),
            'gamejolt_verified_at' => Carbon::now()->toDateTimeString(),
        ];
        
        $user->update($data);
        
        $this->gamejolt_username = $user->gamejolt_username;
        $this->gamejolt_token = $user->gamejolt_token;
        $this->gamejolt_updated_at = $user->gamejolt_updated_at->diffForHumans();
        $this->gamejolt_verified_at = $user->gamejolt_verified_at->diffForHumans();
        
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

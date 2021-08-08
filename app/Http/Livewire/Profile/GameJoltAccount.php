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
    public $username;
    public $token;
    public $updated_at;
    public $verified_at;

    public function mount() {
        $user = Auth::user();
        $this->username = ($user->gamejolt ? $user->gamejolt->username : null);
        $this->token = ($user->gamejolt ? $user->gamejolt->token : null);
        $this->updated_at =  ($user->gamejolt ? $user->gamejolt->updated_at->diffForHumans() : null);
        $this->verified_at =  ($user->gamejolt ? $user->gamejolt->verified_at->diffForHumans() : null);
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
            'username' => [
                'required',
                'alpha_dash',
                'max:30',
                'min:4',
                Rule::unique('game_jolt_accounts')->ignore($user->id, 'user_id'),
            ],
            'token' => [
                'required',
                'alpha_dash',
                'max:30',
                'min:4',
            ],
        ]);

        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        
        try {
            $auth = $api->users()->auth($this->username, $this->token);
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
            'username' => $this->username,
            'token' => $this->token,
            'verified_at' => Carbon::now()->toDateTimeString(),
        ];
        
        $user->gamejolt()->firstOrNew($data);
        
        $this->username = $user->gamejolt->username;
        $this->token = $user->gamejolt->token;
        $this->updated_at = $user->gamejolt->updated_at->diffForHumans();
        $this->verified_at = $user->gamejolt->verified_at->diffForHumans();
        
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

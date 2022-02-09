<?php

namespace App\Http\Livewire\Login;

use App\Models\GamejoltAccount;
use Harrk\GameJoltApi\Exceptions\TimeOutException;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GameJolt extends Component
{
    public $username;

    public $token;

    public function mount()
    {
        $this->username = null;
        $this->token = null;
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

        $this->validate([
            'username' => ['required', 'alpha_dash', 'max:30', 'min:4'],
            'token' => ['required', 'alpha_dash', 'max:30', 'min:4'],
        ]);

        $api = new GamejoltApi(
            new GamejoltConfig(
                env('GAMEJOLT_GAME_ID'),
                env('GAMEJOLT_GAME_PRIVATE_KEY')
            )
        );

        try {
            $auth = $api->users()->auth($this->username, $this->token);
        } catch (TimeOutException $e) {
            $this->addError('error', $e->getMessage());

            return;
        }

        if (
            filter_var(
                $auth['response']['success'],
                FILTER_VALIDATE_BOOLEAN
            ) === false
        ) {
            $error = $auth['response']['message'];
            // Better description of username/token error
            if (
                $error ==
                'No such user with the credentials passed in could be found.'
            ) {
                $error = 'Username and/or token is wrong.';
            }
            $this->addError('error', $error);

            return;
        }

        $gamejoltaccount = GamejoltAccount::where(
            'username',
            $this->username
        )->first();

        if (!$gamejoltaccount) {
            $this->addError(
                'error',
                'This Gamejolt Account is not associated with a P3D account yet.'
            );

            return;
        }

        $user = $gamejoltaccount->user()->first();

        if (!$user) {
            $this->addError(
                'error',
                'Could\'t find the user associated with this Gamejolt Account.'
            );

            return;
        }

        if (!Auth::loginUsingId($user->id)) {
            $this->addError('error', 'Login failed!');

            return;
        } else {
            $gamejoltaccount->touchVerify();
            request()
                ->session()
                ->regenerate();

            return redirect()->intended('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.login.game-jolt');
    }
}

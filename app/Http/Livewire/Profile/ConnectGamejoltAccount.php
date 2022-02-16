<?php

namespace App\Http\Livewire\Profile;

use App\Achievements\User\AssociatedGamejolt;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\GamejoltAccount;
use Illuminate\Validation\Rule;
use Harrk\GameJoltApi\GamejoltApi;
use Illuminate\Support\Facades\Auth;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Support\Facades\Artisan;
use Harrk\GameJoltApi\Exceptions\TimeOutException;

class ConnectGamejoltAccount extends Component
{
    public $username;
    public $token;
    public $updated_at;
    public $verified_at;

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->gamejolt ? $user->gamejolt->username : null;
        $this->token = $user->gamejolt ? $user->gamejolt->token : null;
        $this->updated_at = $user->gamejolt ? $user->gamejolt->updated_at->diffForHumans() : null;
        $this->verified_at = $user->gamejolt ? $user->gamejolt->verified_at->diffForHumans() : null;
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
                'nullable',
                'alpha_dash',
                'max:30',
                'min:4',
                Rule::unique('game_jolt_accounts')->ignore($user->id, 'user_id'),
            ],
            'token' => ['nullable', 'alpha_dash', 'max:30', 'min:4'],
        ]);

        if (!$this->username && !$this->token) {
            $this->errorBag->add('success', 'Your GameJolt account has now been unlinked.');
            $user->gamejolt->delete();
            $this->updated_at = null;
            $this->verified_at = null;
            return;
        }

        $api = new GamejoltApi(new GamejoltConfig(env('GAMEJOLT_GAME_ID'), env('GAMEJOLT_GAME_PRIVATE_KEY')));

        try {
            $auth = $api->users()->auth($this->username, $this->token);
        } catch (TimeOutException $e) {
            $this->addError('error', $e->getMessage());
            return;
        }

        if (filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
            $error = $auth['response']['message'];
            // Better description of username/token error
            if ($error == 'No such user with the credentials passed in could be found.') {
                $error = 'Username and/or token is wrong.';
            }
            $this->addError('error', $error);
            return;
        }

        $gj_user = $api->users()->fetch($this->username, $this->token);
        $id = $gj_user['response']['users'][0]['id'];

        $data = [
            'id' => $id,
            'username' => $this->username,
            'token' => $this->token,
            'verified_at' => Carbon::now()->toDateTimeString(),
            'user_id' => $user->id,
        ];

        $gamejolt = GamejoltAccount::where('user_id', $user->id)
            ->withTrashed()
            ->first();
        if ($gamejolt !== null) {
            $gamejolt->restore();
            $gamejolt->update($data);
        } else {
            $gamejolt = GamejoltAccount::firstOrCreate($data);
        }

        // Update the user's (and other user's) GameJolt Account skin link.
        Artisan::call('p3d:skinuserupdate');

        // Unlock achievement
        $user->unlock(new AssociatedGamejolt());

        $this->username = $gamejolt->username;
        $this->token = $gamejolt->token;
        $this->updated_at = $gamejolt->updated_at->diffForHumans();
        $this->verified_at = $gamejolt->verified_at->diffForHumans();

        $this->emit('saved');
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

        if ($user->gamejolt) {
            $user->gamejolt->delete();
            $this->username = null;
            $this->token = null;
            $this->updated_at = null;
            $this->verified_at = null;
        }

        $this->emit('refresh');

        return;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile.connect-gamejolt-account');
    }
}

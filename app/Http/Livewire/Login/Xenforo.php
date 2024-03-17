<?php

namespace App\Http\Livewire\Login;

use App\Helpers\XenForoHelper;
use App\Models\ForumAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Xenforo extends Component
{
    public ?string $username;

    public ?string $password;

    public function mount(): void
    {
        $this->username = null;
        $this->password = null;
    }

    /**
     * Update the user's Xenforo Account credentials.
     */
    public function save(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $auth = XenForoHelper::postAuth($this->username, $this->password);

        if (isset($auth['error'])) {
            $this->addError('error', $auth['message']);

            return;
        }

        $forum_account = ForumAccount::where('username', $this->username)->first();

        if (! $forum_account) {
            $this->addError('error', 'This Forum Account is not associated with a P3D account yet.');

            return;
        }

        $user = $forum_account->user()->first();

        if (! $user) {
            $this->addError('error', 'Could\'t find the user associated with this Forum Account.');

            return;
        }

        if (! Auth::loginUsingId($user->id)) {
            $this->addError('error', 'Login failed!');

            return;
        } else {
            $forum_account->touchVerify();
            request()
                ->session()
                ->regenerate();
        }

    }

    public function render(): View
    {
        return view('livewire.login.xenforo');
    }
}

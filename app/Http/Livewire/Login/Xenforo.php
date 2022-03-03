<?php

namespace App\Http\Livewire\Login;

use App\Helpers\XenForoHelper;
use App\Models\ForumAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Xenforo extends Component
{
    public $username;

    public $password;

    public function mount()
    {
        $this->username = null;
        $this->password = null;
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
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $auth = XenForoHelper::postAuth($this->username, $this->password);

        if (isset($auth['error'])) {
            $this->addError('error', $auth['message']);

            return;
        }

        $forumaccount = ForumAccount::where('username', $this->username)->first();

        if (!$forumaccount) {
            $this->addError('error', 'This Forum Account is not associated with a P3D account yet.');
            return;
        }

        $user = $forumaccount->user()->first();

        if (!$user) {
            $this->addError('error', 'Could\'t find the user associated with this Forum Account.');
            return;
        }

        if (!Auth::loginUsingId($user->id)) {
            $this->addError('error', 'Login failed!');

            return;
        } else {
            $forumaccount->touchVerify();
            request()
                ->session()
                ->regenerate();

            return redirect()->intended('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.login.xenforo');
    }
}

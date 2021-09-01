<?php

namespace App\Http\Livewire\Login;

use App\Helpers\XenForoHelper;
use Livewire\Component;

class Xenforo extends Component
{
    public $username;
    public $password;

    public function mount() {
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
            'username' => [
                'required',
            ],
            'password' => [
                'required',
            ],
        ]);

        $auth = XenForoHelper::postAuth($this->username, $this->password);
        
        if (isset($auth['error'])) {
            $this->addError('error', $auth['message']);
            return;
        }

        // TODO: Check if there is a user with the xenforo account and log the user in.
        
        return;
    }

    public function render()
    {
        return view('livewire.login.xenforo');
    }
}

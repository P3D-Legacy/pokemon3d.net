<?php

namespace App\Http\Livewire\Profile;

use Carbon\Carbon;
use Livewire\Component;
use App\Helpers\XenForoHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class XenforoAccount extends Component
{
    public $username;
    public $password;
    public $updated_at;
    public $verified_at;

    public function mount() {
        $user = Auth::user();
        $this->username = ($user->forum ? $user->forum->username : null);
        $this->password = ($user->forum ? $user->forum->password : null);
        $this->updated_at =  ($user->forum ? $user->forum->updated_at->diffForHumans() : null);
        $this->verified_at =  ($user->forum ? $user->forum->verified_at->diffForHumans() : null);
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
                Rule::unique('forum_accounts')->ignore($user->id, 'user_id'),
            ],
            'password' => [
                'nullable',
            ],
        ]);

        if (!$this->username && !$this->password) {
            $this->errorBag->add('success', 'Your forum account has now been unlinked.');
            Auth::user()->forum->delete();
            $this->updated_at = null;
            $this->verified_at = null;
            return;
        }

        $auth = XenForoHelper::postAuth($this->username, $this->password);
        
        if (isset($auth['error'])) {
            $this->addError('error', $auth['message']);
            return;
        }

        $data = [
            'username' => $this->username,
            'password' => $this->password,
            'verified_at' => Carbon::now()->toDateTimeString(),
            'user_id' => $user->id,
        ];

        $forum = \App\Models\ForumAccount::where('user_id', $user->id)->withTrashed()->first();
        if ($forum !== null) {
            $forum->restore();
            $forum->update($data);
        } else {
            $forum = \App\Models\ForumAccount::firstOrCreate($data);
        }

        $this->username = $forum->username;
        $this->password = $forum->password;
        $this->updated_at = $forum->updated_at->diffForHumans();
        $this->verified_at = $forum->verified_at->diffForHumans();
        
        $this->emit('saved');
        
        return;
    }
    public function render()
    {
        return view('livewire.profile.xenforo-account');
    }
}

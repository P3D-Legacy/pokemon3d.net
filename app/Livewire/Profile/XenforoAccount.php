<?php

namespace App\Livewire\Profile;

use App\Helpers\XenForoHelper;
use App\Models\ForumAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class XenforoAccount extends Component
{
    public $username;

    public $password;

    public $updated_at;

    public $verified_at;

    public bool $syncRegisterDate;

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->forum ? $user->forum->username : null;
        $this->password = $user->forum ? $user->forum->password : null;
        $this->updated_at = $user->forum ? $user->forum->updated_at->diffForHumans() : null;
        $this->verified_at = $user->forum ? $user->forum->verified_at->diffForHumans() : null;
        $this->syncRegisterDate = false;
    }

    /**
     * Update the user's Xenforo Account credentials.
     */
    public function save(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $user = Auth::user();

        $this->validate([
            'username' => ['nullable', Rule::unique('forum_accounts')->ignore($user->id, 'user_id')],
            'password' => ['nullable'],
            'syncRegisterDate' => ['boolean'],
        ]);

        if (! $this->username && ! $this->password) {
            $this->errorBag->add('success', 'Your forum account has now been unlinked.');
            if ($user->forum()) {
                $user->forum()->delete();
            }
            $this->updated_at = null;
            $this->verified_at = null;

            return;
        }

        $auth = XenForoHelper::postAuth($this->username, $this->password);

        if (isset($auth['error'])) {
            $this->addError('error', $auth['message'] ?? 'An unknown error occurred.');

            return;
        }

        $data = [
            'username' => $this->username,
            'password' => $this->password,
            'verified_at' => Carbon::now()->toDateTimeString(),
            'user_id' => $user->id,
        ];

        $forum = ForumAccount::where('user_id', $user->id)
            ->withTrashed()
            ->first();
        if ($forum !== null) {
            $forum->restore();
            $forum->update($data);
        } else {
            $forum = ForumAccount::firstOrCreate($data);
        }

        if ($this->syncRegisterDate) {
            $register_date = Carbon::parse($auth['user']['register_date']);
            $user->update([
                'created_at' => $register_date,
            ]);
        }

        $this->username = $forum->username;
        $this->password = $forum->password;
        $this->updated_at = $forum->updated_at->diffForHumans();
        $this->verified_at = $forum->verified_at->diffForHumans();

        $this->dispatch('saved');
    }

    /**
     * Update the user's Xenforo Account credentials.
     */
    public function remove(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $user = Auth::user();

        if ($user->forum()) {
            $user->forum()->delete();
            $this->username = null;
            $this->password = null;
            $this->updated_at = null;
            $this->verified_at = null;
        }

        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.profile.xenforo-account');
    }
}

<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Consent extends Component
{
    public $consents = [];

    public function mount() {
        $this->consents = config('app.consents');
    }
        
    /**
     * Display a view.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return view('livewire.profile.consent');
    }

    public function consentGiven($consent) {
        $user = Auth::user();

        return $user->hasGivenConsent($consent);
    }

    public function toggleConsent($consent) {
        $user = Auth::user();

        if ($user->hasGivenConsent($consent)) {
            return $user->revokeConsentTo($consent);
        }

        return $user->giveConsentTo($consent, [
            'text' => $this->consents[$consent],
        ]);

    }
}

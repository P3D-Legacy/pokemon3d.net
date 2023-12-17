<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Consent extends Component
{
    public array $consents = [];

    public function mount(): void
    {
        $this->consents = config('app.consents');
    }

    /**
     * Display a view.
     */
    public function render(): View
    {
        return view('livewire.profile.consent');
    }

    public function consentGiven($consent)
    {
        $user = Auth::user();

        return $user->hasGivenConsent($consent);
    }

    public function toggleConsent($consent)
    {
        $user = Auth::user();

        if ($user->hasGivenConsent($consent)) {
            return $user->revokeConsentTo($consent);
        }

        return $user->giveConsentTo($consent, [
            'text' => $this->consents[$consent],
        ]);
    }
}

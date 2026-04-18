<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewTermsBanner extends Component
{
    public function agree()
    {
        Auth::user()->giveConsentTo(config('app.required_consent'), [
            'text' => config('app.consents')[config('app.required_consent')],
        ]);
        redirect()->route('profile.show');
    }

    /**
     * Display a view.
     */
    public function render(): View
    {
        return view('livewire.new-terms-banner');
    }
}

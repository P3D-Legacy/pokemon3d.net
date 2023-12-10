<?php

namespace App\Http\Livewire;

use Illuminate\Http\Response;
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
     *
     * @return \Illuminate\Http\Response
     */
    public function render(): Response
    {
        return view('livewire.new-terms-banner');
    }
}

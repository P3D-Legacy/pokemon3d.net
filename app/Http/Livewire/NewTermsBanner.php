<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NewTermsBanner extends Component
{
    public function agree()
    {
        Auth::user()->giveConsentTo(config("app.required_consent"), [
            "text" => config("app.consents")[config("app.required_consent")],
        ]);
        redirect()->route("profile.show");
    }

    /**
     * Display a view.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return view("livewire.new-terms-banner");
    }
}

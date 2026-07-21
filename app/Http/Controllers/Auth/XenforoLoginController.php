<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateForumUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\XenforoLoginRequest;
use App\Providers\AppServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class XenforoLoginController extends Controller
{
    public function store(XenforoLoginRequest $request, AuthenticateForumUser $authenticate): RedirectResponse
    {
        if (! filled(config('services.xenforo.api_key')) || ! filled(config('services.xenforo.api_url'))) {
            return back()->with('error', 'Forum login is not available.');
        }

        $result = $authenticate($request->string('username')->toString(), $request->string('password')->toString());

        if (is_string($result)) {
            return back()->withErrors(['error' => $result]);
        }

        Auth::login($result);
        $request->session()->regenerate();

        return redirect()->intended(AppServiceProvider::HOME);
    }
}

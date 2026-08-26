<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateGameJoltUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GameJoltLoginRequest;
use App\Providers\AppServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class GameJoltLoginController extends Controller
{
    public function store(GameJoltLoginRequest $request, AuthenticateGameJoltUser $authenticate): RedirectResponse
    {
        if (! filled(config('services.gamejolt.private_key')) || ! filled(config('services.gamejolt.game_id'))) {
            return back()->with('error', 'Game Jolt login is not available.');
        }

        $result = $authenticate($request->string('username')->toString(), $request->string('token')->toString());

        if (is_string($result)) {
            return back()->withErrors(['error' => $result]);
        }

        Auth::login($result);
        $request->session()->regenerate();

        return redirect()->intended(AppServiceProvider::HOME);
    }
}

<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SocialAccountController extends Controller
{
    /**
     * Disconnect a linked social account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider' => ['required', Rule::in(['discord', 'facebook', 'twitch', 'gamejolt'])],
        ]);

        $user = $request->user();
        $relation = $validated['provider'];

        if ($user->{$relation}) {
            $user->{$relation}->delete();
        }

        return back()->with('status', __('Social account disconnected.'));
    }
}

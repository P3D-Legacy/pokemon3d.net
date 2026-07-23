<?php

namespace App\Http\Controllers\Profile;

use App\Actions\Profile\LinkGamejoltAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\LinkGamejoltAccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SocialAccountController extends Controller
{
    /**
     * Link a social account to the authenticated user.
     */
    public function store(LinkGamejoltAccountRequest $request, LinkGamejoltAccount $linkGamejoltAccount): RedirectResponse
    {
        $result = $linkGamejoltAccount(
            $request->user(),
            $request->string('username')->toString(),
            $request->string('token')->toString(),
        );

        if (is_string($result)) {
            return back()->withErrors(['error' => $result]);
        }

        return back()->with('status', __('Game Jolt account linked.'));
    }

    /**
     * Disconnect a linked social account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider' => ['required', Rule::in(['discord', 'twitch', 'gamejolt'])],
        ]);

        $user = $request->user();
        $relation = $validated['provider'];

        if ($user->{$relation}) {
            $user->{$relation}->delete();
        }

        return back()->with('status', __('Social account disconnected.'));
    }
}

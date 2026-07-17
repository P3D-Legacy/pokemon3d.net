<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * Toggle a profile preference setting.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'setting' => ['required', 'string'],
        ]);

        $user = $request->user();
        $settings = $user->settings()->all();

        abort_unless(array_key_exists($validated['setting'], $settings), 404);

        $user->settings()->set(
            $validated['setting'],
            ! $user->settings()->get($validated['setting'])
        );

        return back();
    }
}

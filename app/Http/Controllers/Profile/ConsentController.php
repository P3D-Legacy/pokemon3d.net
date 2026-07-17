<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConsentController extends Controller
{
    /**
     * Toggle a consent flag for the authenticated user.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'consent' => ['required', 'string'],
        ]);

        $consents = config('app.consents', []);

        abort_unless(array_key_exists($validated['consent'], $consents), 404);

        $user = $request->user();

        if ($user->hasGivenConsent($validated['consent'])) {
            $user->revokeConsentTo($validated['consent']);
        } else {
            $user->giveConsentTo($validated['consent'], [
                'text' => $consents[$validated['consent']],
            ]);
        }

        return back();
    }
}

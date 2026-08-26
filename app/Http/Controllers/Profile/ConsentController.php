<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConsentController extends Controller
{
    /**
     * Toggle an optional consent flag for the authenticated user.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'consent' => ['required', 'string'],
        ]);

        $consents = config('app.consents', []);
        $consent = $validated['consent'];
        $requiredConsent = config('app.required_consent');

        abort_unless(array_key_exists($consent, $consents), 404);

        $user = $request->user();

        if ($consent === $requiredConsent) {
            if (! $user->hasGivenConsent($consent)) {
                $user->giveConsentTo($consent, [
                    'text' => $consents[$consent],
                ]);
            }

            return back();
        }

        if ($user->hasGivenConsent($consent)) {
            $user->revokeConsentTo($consent);
        } else {
            $user->giveConsentTo($consent, [
                'text' => $consents[$consent],
            ]);
        }

        return back();
    }

    /**
     * Accept the required terms of service consent.
     */
    public function acceptRequired(Request $request): RedirectResponse
    {
        $consent = config('app.required_consent');
        $consents = config('app.consents', []);

        abort_unless(is_string($consent) && array_key_exists($consent, $consents), 404);

        $user = $request->user();

        if (! $user->hasGivenConsent($consent)) {
            $user->giveConsentTo($consent, [
                'text' => $consents[$consent],
            ]);
        }

        return back();
    }
}

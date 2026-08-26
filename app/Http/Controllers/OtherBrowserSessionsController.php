<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmPassword;

class OtherBrowserSessionsController extends Controller
{
    /**
     * Log out from other browser sessions.
     */
    public function destroy(Request $request, StatefulGuard $guard): RedirectResponse
    {
        $confirmed = app(ConfirmPassword::class)(
            $guard,
            $request->user(),
            $request->password
        );

        if (! $confirmed) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
        }

        $guard->logoutOtherDevices($request->password);

        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        return back(303);
    }
}

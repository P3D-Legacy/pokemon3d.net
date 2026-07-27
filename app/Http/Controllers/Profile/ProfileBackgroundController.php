<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileBackgroundRequest;
use Illuminate\Http\RedirectResponse;

class ProfileBackgroundController extends Controller
{
    /**
     * Update the website profile background override.
     */
    public function update(UpdateProfileBackgroundRequest $request): RedirectResponse
    {
        $slug = $request->validated('profile_background');

        $request->user()->forceFill([
            'profile_background' => filled($slug) ? strtolower(trim((string) $slug)) : null,
        ])->save();

        return back();
    }
}

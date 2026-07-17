<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('api-tokens/index', [
            'tokens' => $request->user()->tokens->map(fn ($token): array => [
                ...$token->toArray(),
                'last_used_ago' => optional($token->last_used_at)->diffForHumans(),
            ])->values(),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'plainTextToken' => session('flash.token'),
        ]);
    }

    /**
     * Create a new API token.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        $token = $request->user()->createToken(
            $request->name,
            Jetstream::validPermissions($request->input('permissions', []))
        );

        return back()->with('flash', [
            'token' => explode('|', $token->plainTextToken, 2)[1],
        ]);
    }

    /**
     * Update the given API token's permissions.
     */
    public function update(Request $request, string $tokenId): RedirectResponse
    {
        $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => Jetstream::validPermissions($request->input('permissions', [])),
        ])->save();

        return back(303);
    }

    /**
     * Delete the given API token.
     */
    public function destroy(Request $request, string $tokenId): RedirectResponse
    {
        $request->user()->tokens()->where('id', $tokenId)->firstOrFail()->delete();

        return back(303);
    }
}

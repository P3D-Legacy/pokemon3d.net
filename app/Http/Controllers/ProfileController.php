<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Agent;
use Laravel\Jetstream\Jetstream;

class ProfileController extends Controller
{
    /**
     * Show the profile settings screen.
     */
    public function show(Request $request): Response
    {
        $user = $request->user()->loadMissing(['discord', 'facebook', 'twitch', 'gamejolt']);

        return Inertia::render('profile/edit', [
            'profile' => [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'gender' => (int) $user->gender,
                'location' => $user->location,
                'about' => $user->about,
                'birthdate' => optional($user->birthdate)->format('d-m-Y'),
                'timezone' => $user->timezone,
                'created_at_utc' => $user->created_at->setTimezone('UTC')->format('Y-m-d H:i:s'),
                'created_at_local' => $user->created_at->setTimezone($user->timezone ?: 'UTC')->format('Y-m-d H:i:s'),
                'profile_photo_url' => $user->profile_photo_url,
                'two_factor_enabled' => ! is_null($user->two_factor_secret),
                'email_verified_at' => $user->email_verified_at,
            ],
            'sessions' => $this->sessions($request),
            'preferences' => $user->settings()->all(),
            'consents' => collect(config('app.consents', []))
                ->map(fn (string $text, string $key): array => [
                    'key' => $key,
                    'text' => $text,
                    'given' => $user->hasGivenConsent($key),
                ])
                ->values()
                ->all(),
            'socialAccounts' => [
                'discord' => [
                    'enabled' => filled(config('services.discord.client_id')) && filled(config('services.discord.client_secret')),
                    'connected' => (bool) $user->discord,
                    'label' => $user->discord?->username,
                    'connect_url' => route('discord.login'),
                ],
                'facebook' => [
                    'enabled' => filled(config('services.facebook.client_id')) && filled(config('services.facebook.client_secret')),
                    'connected' => (bool) $user->facebook,
                    'label' => $user->facebook?->name,
                    'connect_url' => route('facebook.login'),
                ],
                'twitch' => [
                    'enabled' => filled(config('services.twitch.client_id')) && filled(config('services.twitch.client_secret')),
                    'connected' => (bool) $user->twitch,
                    'label' => $user->twitch?->name,
                    'connect_url' => route('twitch.login'),
                ],
                'gamejolt' => [
                    'enabled' => filled(config('services.gamejolt.game_id')) && filled(config('services.gamejolt.private_key')),
                    'connected' => (bool) $user->gamejolt,
                    'label' => $user->gamejolt?->username,
                    'connect_url' => null,
                ],
            ],
            'features' => [
                'canUpdateProfileInformation' => Features::canUpdateProfileInformation(),
                'canUpdatePasswords' => Features::enabled(Features::updatePasswords()),
                'canManageTwoFactorAuthentication' => Features::canManageTwoFactorAuthentication(),
                'managesProfilePhotos' => Jetstream::managesProfilePhotos(),
                'hasAccountDeletionFeatures' => Jetstream::hasAccountDeletionFeatures(),
            ],
            'status' => session('status'),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function sessions(Request $request): array
    {
        if (config('session.driver') !== 'database') {
            return [];
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request): array {
            $agent = tap(new Agent, fn (Agent $agent) => $agent->setUserAgent($session->user_agent));

            return [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform() ?: 'Unknown',
                    'browser' => $agent->browser() ?: 'Unknown',
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        })->values()->all();
    }
}

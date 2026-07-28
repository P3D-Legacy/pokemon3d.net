<?php

namespace App\Http\Middleware;

use App\Support\JsonTranslations;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $requiredConsent = config('app.required_consent');
        $needsTermsAcceptance = $user
            && is_string($requiredConsent)
            && ! $user->hasGivenConsent($requiredConsent);

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user
                    ? [
                        ...$user->only(['id', 'name', 'username', 'email', 'profile_photo_url', 'email_verified_at']),
                        'unread_notifications_count' => $user->unreadNotifications()->count(),
                        'is_admin' => $user->hasAnyRole(['super-admin', 'admin']),
                    ]
                    : null,
            ],
            'termsAcceptance' => $needsTermsAcceptance
                ? [
                    'required' => true,
                    'key' => $requiredConsent,
                    'text' => config('app.consents.'.$requiredConsent),
                ]
                : null,
            'flash' => [
                'status' => fn () => $request->session()->get('status'),
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
                'banner' => fn () => $request->session()->get('flash.banner'),
                'bannerStyle' => fn () => $request->session()->get('flash.bannerStyle'),
                'token' => fn () => $request->session()->get('flash.token'),
            ],
            'appName' => config('app.name'),
            'locale' => app()->getLocale(),
            'env' => config('app.env'),
            'languages' => $this->sharedLanguages(),
            'translations' => fn () => JsonTranslations::forLocale(),
            'socialLogin' => [
                'discord' => filled(config('services.discord.client_id')) && filled(config('services.discord.client_secret')),
                'twitch' => filled(config('services.twitch.client_id')) && filled(config('services.twitch.client_secret')),
                'gamejolt' => filled(config('services.gamejolt.private_key')),
                'xenforo' => filled(config('services.xenforo.api_key')) && filled(config('services.xenforo.api_url')),
            ],
        ];
    }

    /**
     * @return array{
     *     current: string,
     *     current_name: string,
     *     current_flag: string,
     *     options: list<array{code: string, name: string, flag: string, url: string}>,
     *     contribute_url: string,
     *     contribute_label: string
     * }
     */
    private function sharedLanguages(): array
    {
        $locale = app()->getLocale();
        $codes = config('app.env') === 'production'
            ? config('language.done', [])
            : config('language.allowed', []);

        $options = collect($codes)
            ->map(fn (string $code): array => [
                'code' => $code,
                'name' => language()->getName($code),
                'flag' => language()->country($code),
                'url' => language()->back($code),
            ])
            ->values()
            ->all();

        return [
            'current' => $locale,
            'current_name' => language()->getName($locale),
            'current_flag' => language()->country($locale),
            'options' => $options,
            'contribute_url' => config('app.lang_contribution_url') ?: '#',
            'contribute_label' => __('Contribute your language').'!',
        ];
    }
}

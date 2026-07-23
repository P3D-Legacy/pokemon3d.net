<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\OtherBrowserSessionsController;
use App\Http\Controllers\Profile\ConsentController;
use App\Http\Controllers\Profile\PreferenceController;
use App\Http\Controllers\Profile\SocialAccountController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Jetstream;

Route::middleware(config('jetstream.middleware', ['web']))->group(function () {
    if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms', [LegalController::class, 'terms'])->name('terms.show');
        Route::get('/privacy', [LegalController::class, 'policy'])->name('policy.show');
    }

    Route::middleware('auth')->group(function () {
        Route::post('/user/consents/required', [ConsentController::class, 'acceptRequired'])->name('profile.consents.accept-required');
    });

    Route::middleware('auth', 'verified')->group(function () {
        Route::get('/user/edit/profile', [ProfileController::class, 'show'])->name('profile.show');

        Route::delete('/user/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])
            ->name('other-browser-sessions.destroy');

        if (Jetstream::hasAccountDeletionFeatures()) {
            Route::delete('/user', [CurrentUserController::class, 'destroy'])->name('current-user.destroy');
        }

        Route::patch('/user/preferences', [PreferenceController::class, 'update'])->name('profile.preferences.update');
        Route::patch('/user/consents', [ConsentController::class, 'update'])->name('profile.consents.update');
        Route::post('/user/social-accounts/gamejolt', [SocialAccountController::class, 'store'])->name('profile.social.gamejolt.store');
        Route::delete('/user/social-accounts', [SocialAccountController::class, 'destroy'])->name('profile.social.destroy');

        if (Jetstream::hasApiFeatures()) {
            Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
            Route::post('/user/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
            Route::put('/user/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
            Route::delete('/user/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
        }
    });
});

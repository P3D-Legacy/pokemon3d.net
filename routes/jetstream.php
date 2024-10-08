<?php

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Livewire\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Livewire\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Livewire\TeamController;
use Laravel\Jetstream\Http\Controllers\Livewire\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Jetstream;

Route::middleware(config('jetstream.middleware', ['web']))->group(function () {
    if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/privacy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    Route::middleware('auth', 'verified')->group(function () {
        // User & Profile...
        Route::get('/user/edit/profile', function () {
            return view('profile.edit', [
                'request' => request(),
                'user' => request()->user(),
            ]);
        })->name('profile.show');

        // API...
        if (Jetstream::hasApiFeatures()) {
            Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
        }

        // Teams...
        if (Jetstream::hasTeamFeatures()) {
            Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
            Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
            Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');

            Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
                ->middleware(['signed'])
                ->name('team-invitations.accept');
        }
    });
});

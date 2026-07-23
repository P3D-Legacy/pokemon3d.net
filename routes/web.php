<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\GameJoltLoginController;
use App\Http\Controllers\Auth\TwitchController;
use App\Http\Controllers\Auth\XenforoLoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceUpdateController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Save\MySaveController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\Skin\PlayerSkinController;
use App\Http\Controllers\Skin\SkinController;
use App\Http\Controllers\Skin\SkinHomeController;
use App\Http\Controllers\Skin\UploadedSkinController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (config('app.debug')) {
    Route::get('/test', function () {
        return 'test';
    });
}
Route::prefix('redirect')->group(function () {
    Route::get('/wiki', function () {
        return Inertia::location('https://wiki.pokemon3d.net/');
    })->name('wiki');

    Route::get('/forum', function () {
        return Inertia::location('https://forum.pokemon3d.net/');
    })->name('forum');

    Route::get('/github', function () {
        return Inertia::location('https://github.com/P3D-Legacy');
    })->name('github');

    Route::get('/discord', function () {
        return Inertia::location(config('services.discord.invite_url'));
    })->name('discord');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/legal', [LegalController::class, 'legal'])->name('legal');
Route::get('/contact', [LegalController::class, 'contact'])->name('contact');
Route::resource('blog', BlogController::class);
Route::get('/download', [DownloadController::class, 'download'])->name('download');

Route::prefix('login')->group(function () {
    Route::get('/discord', [DiscordController::class, 'redirectToProvider'])->name('discord.login');
    Route::get('/discord/callback', [DiscordController::class, 'handleProviderCallback']);
    Route::get('/facebook', [FacebookController::class, 'redirectToProvider'])->name('facebook.login');
    Route::get('/facebook/callback', [FacebookController::class, 'handleProviderCallback']);
    Route::get('/twitch', [TwitchController::class, 'redirectToProvider'])->name('twitch.login');
    Route::get('/twitch/callback', [TwitchController::class, 'handleProviderCallback']);

    Route::middleware('guest')->group(function () {
        Route::post('/gamejolt', [GameJoltLoginController::class, 'store'])
            ->middleware('throttle:login')
            ->name('gamejolt.login');
        Route::post('/forum', [XenforoLoginController::class, 'store'])
            ->middleware('throttle:login')
            ->name('forum.login');
    });
});

Route::get('/review', [ReviewController::class, 'index'])->name('review');
Route::post('/review', [ReviewController::class, 'store'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('review.store');

Route::resource('server', ServerController::class);
Route::post('/server/{server}/reactivate', [ServerController::class, 'reactivate'])
    ->middleware(['auth', 'verified'])
    ->name('server.reactivate');

Route::prefix('resource')->group(function () {
    Route::get('/', [ResourceController::class, 'index'])->name('resource.index');
    Route::get('/category/{name}', [ResourceController::class, 'index'])->name('resource.category');

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/create', [ResourceController::class, 'create'])->name('resource.create');
        Route::post('/', [ResourceController::class, 'store'])->name('resource.store');

        Route::get('/{uuid}/edit', [ResourceController::class, 'edit'])->name('resource.edit');
        Route::put('/{uuid}', [ResourceController::class, 'update'])->name('resource.update');
        Route::patch('/{uuid}', [ResourceController::class, 'update']);

        Route::get('/{uuid}/delete', [ResourceController::class, 'delete'])->name('resource.delete');
        Route::delete('/{uuid}', [ResourceController::class, 'destroy'])->name('resource.destroy');

        Route::get('/{uuid}/updates/create', [ResourceUpdateController::class, 'create'])->name('resource.updates.create');
        Route::post('/{uuid}/updates', [ResourceUpdateController::class, 'store'])->name('resource.updates.store');

        Route::get('/{uuid}/rate', [ResourceController::class, 'rate'])->name('resource.rate');
        Route::post('/{uuid}/rate', [ResourceController::class, 'storeRating'])->name('resource.rate.store');

        Route::post('/{uuid}/like', [ResourceController::class, 'like'])->name('resource.like');
    });

    Route::get('/{uuid}/updates/{update}/download', [ResourceUpdateController::class, 'download'])
        ->name('resource.updates.download');
    Route::get('/{uuid}', [ResourceController::class, 'show'])->name('resource.uuid');
});
Route::get('/members', [MemberController::class, 'index'])->name('member.index');
Route::get('/members/{user}', [MemberController::class, 'show'])->name('member.show');
// Fallback for old member links
Route::get('/member/{user}', function ($user) {
    return redirect()->route('member.show', $user);
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth:sanctum', 'verified'])
    ->name('dashboard');

Route::prefix('skin')
    ->group(function () {
        Route::middleware(['auth:sanctum', 'verified', 'gj.association'])->group(function () {
            Route::get('/', [SkinHomeController::class, 'index'])->name('skin-home');
            Route::get('/my', function () {
                return redirect()->route('skin-home');
            })->name('skins-my');

            Route::get('/player', [PlayerSkinController::class, 'index'])->name('player-skins');
            Route::post('/player/create', [PlayerSkinController::class, 'store'])->name('player-skin-store');
            Route::post('/player/duplicate', [PlayerSkinController::class, 'duplicate'])->name('player-skin-duplicate');
            Route::delete('/player/delete/{id}', [PlayerSkinController::class, 'destroyAsAdmin'])->name(
                'player-skin-destroy-admin'
            );
            Route::delete('/player/delete', [PlayerSkinController::class, 'destroy'])->name('player-skin-destroy');

            Route::get('/create', [SkinController::class, 'create'])->name('skin-create');
            Route::post('/create', [SkinController::class, 'store'])->name('skin-store');

            Route::get('/uploaded', [UploadedSkinController::class, 'index'])->name('uploaded-skins');
            Route::delete('/uploaded/delete/{id}', [UploadedSkinController::class, 'destroy'])->name('uploaded-skin-destroy');

            Route::get('/{uuid}/edit', [SkinController::class, 'edit'])->name('skin-edit');
            Route::post('/{uuid}/edit', [SkinController::class, 'update'])->name('skin-update');
            Route::delete('/{uuid}/delete', [SkinController::class, 'destroy'])->name('skin-destroy');
            Route::post('/{uuid}/apply', [SkinController::class, 'apply'])->name('skin-apply');
            Route::post('/{uuid}/like', [SkinController::class, 'like'])->name('skin-like');
        });

        Route::get('/public', function () {
            return redirect()->route('skins-newest');
        })->name('skins');
        Route::get('/public/new', [SkinController::class, 'newestpublicskins'])->name('skins-newest');
        Route::get('/public/popular', [SkinController::class, 'popularpublicskins'])->name('skins-popular');
        Route::get('/public/{skin}', [SkinController::class, 'show'])->name('skin-show');
    });

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/open', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/{id}/dismiss', [NotificationController::class, 'dismiss'])->name('notifications.dismiss');
    Route::post('/notifications/dismiss-all', [NotificationController::class, 'dismissAll'])->name('notifications.dismiss-all');

    if (config('app.env') === 'staging' or config('app.env') === 'local') {
        Route::prefix('save')->middleware('gj.association')->group(function () {
            Route::get('/', [MySaveController::class, 'index'])->name('save.index');
        });
    }

    Route::prefix('mod')
        ->middleware(['role:super-admin|admin'])
        ->group(function () {
            Route::resource('tags', TagController::class);
            Route::get('/analytics', AnalyticsController::class)
                ->name('analytics')
                ->middleware(['permission:analytics']);
        });
});

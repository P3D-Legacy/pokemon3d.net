<?php

use AliBayat\LaravelCategorizable\Category;
use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\TwitchController;
use App\Http\Controllers\Auth\TwitterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\Skin\ImportController;
use App\Http\Controllers\Skin\PlayerSkinController;
use App\Http\Controllers\Skin\SkinController;
use App\Http\Controllers\Skin\SkinHomeController;
use App\Http\Controllers\Skin\UploadedSkinController;
use App\Http\Controllers\TagController;
use App\Http\Livewire\Resource\ResourceShow;
use Illuminate\Support\Facades\Route;

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
        return redirect('https://wiki.pokemon3d.net/');
    })->name('wiki');

    Route::get('/forum', function () {
        return redirect('https://forum.pokemon3d.net/');
    })->name('forum');

    Route::get('/github', function () {
        return redirect('https://github.com/P3D-Legacy');
    })->name('github');

    Route::get('/discord', function () {
        return redirect(config('services.discord.invite_url'));
    })->name('discord');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('blog', BlogController::class);
Route::get('/download', [DownloadController::class, 'download'])->name('download');

Route::prefix('login')->group(function () {
    Route::get('/discord', [DiscordController::class, 'redirectToProvider'])->name('discord.login');
    Route::get('/discord/callback', [DiscordController::class, 'handleProviderCallback']);
    Route::get('/twitter', [TwitterController::class, 'redirectToProvider'])->name('twitter.login');
    Route::get('/twitter/callback', [TwitterController::class, 'handleProviderCallback']);
    Route::get('/facebook', [FacebookController::class, 'redirectToProvider'])->name('facebook.login');
    Route::get('/facebook/callback', [FacebookController::class, 'handleProviderCallback']);
    Route::get('/twitch', [TwitchController::class, 'redirectToProvider'])->name('twitch.login');
    Route::get('/twitch/callback', [TwitchController::class, 'handleProviderCallback']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/notifications', \App\Http\Livewire\NotificationList::class)->name('notifications.index');

    Route::get('/member/{user}', [MemberController::class, 'show'])->name('member.show');

    Route::get('/review', function () {
        return view('review.index');
    })->name('review');

    Route::resource('server', ServerController::class);

    Route::prefix('resource')->group(function () {
        Route::get('/', function () {
            return view('resources.index', [
                'categories' => Category::where('parent_id', null)->get(),
            ]);
        })->name('resource.index');

        Route::get('/{uuid}', ResourceShow::class)->name('resource.uuid');

        Route::get('/category/{name}', function ($name) {
            return view('resources.index', [
                'categories' => Category::where('parent_id', null)->get(),
                'category' => Category::findByName($name),
            ]);
        })->name('resource.category');
    });

    Route::prefix('skin')
        ->middleware('gj.association')
        ->group(function () {
            Route::get('/', [SkinHomeController::class, 'index'])->name('skin-home');
            Route::get('/my', function () {
                return redirect()->route('skin-home');
            })->name('skins-my');

            Route::get('/import/{id}', [ImportController::class, 'import'])->name('import');

            Route::get('/player', [PlayerSkinController::class, 'index'])->name('player-skins');
            Route::post('/player/create', [PlayerSkinController::class, 'store'])->name('player-skin-store');
            Route::get('/player/duplicate', [PlayerSkinController::class, 'duplicate'])->name('player-skin-duplicate');
            Route::post('/player/delete/{id}', [PlayerSkinController::class, 'destroyAsAdmin'])->name(
                'player-skin-destroy-admin'
            );
            Route::get('/player/delete', [PlayerSkinController::class, 'destroy'])->name('player-skin-destroy');

            Route::get('/public', function () {
                return redirect()->route('skins-newest');
            })->name('skins');
            Route::get('/public/new', [SkinController::class, 'newestpublicskins'])->name('skins-newest');
            Route::get('/public/popular', [SkinController::class, 'popularpublicskins'])->name('skins-popular');
            Route::get('/public/{skin}', [SkinController::class, 'show'])->name('skin-show');
            Route::get('/create', [SkinController::class, 'create'])->name('skin-create');
            Route::post('/create', [SkinController::class, 'store'])->name('skin-store');
            Route::get('/{uuid}/edit', [SkinController::class, 'edit'])->name('skin-edit');
            Route::post('/{uuid}/edit', [SkinController::class, 'update'])->name('skin-update');
            Route::get('/{uuid}/delete', [SkinController::class, 'destroy'])->name('skin-destroy');
            Route::get('/{uuid}/apply', [SkinController::class, 'apply'])->name('skin-apply');
            Route::get('/{uuid}/like', [SkinController::class, 'like'])->name('skin-like');

            Route::get('/uploaded', [UploadedSkinController::class, 'index'])->name('uploaded-skins');
            Route::post('/uploaded/delete/{id}', [UploadedSkinController::class, 'destroy'])->name('uploaded-skin-destroy');
        });

    if(config('app.env') === 'staging' or config('app.env') === 'local') {
        Route::prefix('save')->middleware('gj.association')->group(function () {
            Route::get('/', [\App\Http\Controllers\Save\MySaveController::class, 'index'])->name('save.index');
        });
    }

    Route::prefix('mod')
        ->middleware(['role:super-admin|admin'])
        ->group(function () {
            Route::resource('tags', TagController::class);
            Route::get('/analytics', \App\Http\Livewire\Analytics::class)
                ->name('analytics')
                ->middleware(['permission:analytics']);
        });
});

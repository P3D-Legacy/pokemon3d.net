<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Skin\SkinController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Skin\ImportController;
use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Skin\SkinHomeController;
use App\Http\Controllers\Skin\PlayerSkinController;
use App\Http\Controllers\Skin\UploadedSkinController;

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

Route::get('/redirect/wiki', function () {
    return redirect('https://pokemon3d.net/wiki/');
})->name('wiki');

Route::get('/redirect/forum', function () {
    return redirect('https://pokemon3d.net/forum/');
})->name('forum');


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('blog', BlogController::class);

Route::group(['prefix' => 'login'], function () {
    Route::get('/discord', [DiscordController::class, 'redirectToProvider'])->name('discord.login');
    Route::get('/discord/callback', [DiscordController::class, 'handleProviderCallback']);
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/member/{user}', [MemberController::class, 'show'])->name('member.show');
    
    Route::prefix('skin')->group(function () {
        Route::get('/', [SkinHomeController::class, 'index'])->name('skin-home');
        Route::get('/my', function() {
            return redirect()->route('skin-home');
        })->name('skins-my');
    
        Route::get('/import/{id}', [ImportController::class, 'import'])->name('import');
        
        Route::get('/player', [PlayerSkinController::class, 'index'])->name('player-skins');
        Route::post('/player/create', [PlayerSkinController::class, 'store'])->name('player-skin-store');
        Route::get('/player/duplicate', [PlayerSkinController::class, 'duplicate'])->name('player-skin-duplicate');
        Route::post('/player/delete/{id}', [PlayerSkinController::class, 'destroyAsAdmin'])->name('player-skin-destroy-admin');
        Route::get('/player/delete', [PlayerSkinController::class, 'destroy'])->name('player-skin-destroy');
        
        Route::get('/public', function(){ return redirect()->route('skins-newest');})->name('skins');
        Route::get('/public/new', [SkinController::class, 'newestpublicskins'])->name('skins-newest');
        Route::get('/public/popular', [SkinController::class, 'popularpublicskins'])->name('skins-popular');
        Route::get('/public/{uuid}', [SkinController::class, 'show'])->name('skin-show');
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

    Route::prefix('admin')->middleware(['role:super-admin|admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('posts', PostController::class);
        Route::resource('tags', TagController::class);
    });
    
});



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PlayerSkinController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login-post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/import/{id}', [ImportController::class, 'import'])->name('import');

Route::get('/player/skins', [PlayerSkinController::class, 'index'])->name('player-skins');
Route::post('/player/skin/create', [PlayerSkinController::class, 'store'])->name('player-skin-store');
Route::get('/player/skin/duplicate', [PlayerSkinController::class, 'duplicate'])->name('player-skin-duplicate');
Route::post('/player/skin/delete/{id}', [PlayerSkinController::class, 'destroyAsAdmin'])->name('player-skin-destroy-admin');
Route::get('/player/skin/delete', [PlayerSkinController::class, 'destroy'])->name('player-skin-destroy');

Route::get('/skins/public', function(){ return redirect()->route('skins-newest');})->name('skins');
Route::get('/skins/public/new', [SkinController::class, 'newestpublicskins'])->name('skins-newest');
Route::get('/skins/public/popular', [SkinController::class, 'popularpublicskins'])->name('skins-popular');
Route::get('/skins/public/{uuid}', [SkinController::class, 'show'])->name('skin-show');
Route::get('/skins/my', [SkinController::class, 'myskins'])->name('skins-my');
Route::get('/skin/create', [SkinController::class, 'create'])->name('skin-create');
Route::post('/skin/create', [SkinController::class, 'store'])->name('skin-store');
Route::get('/skin/{uuid}/edit', [SkinController::class, 'edit'])->name('skin-edit');
Route::post('/skin/{uuid}/edit', [SkinController::class, 'update'])->name('skin-update');
Route::get('/skin/{uuid}/delete', [SkinController::class, 'destroy'])->name('skin-destroy');
Route::get('/skin/{uuid}/apply', [SkinController::class, 'apply'])->name('skin-apply');
Route::get('/skin/{uuid}/like', [SkinController::class, 'like'])->name('skin-like');

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/user/edit/{gjid}', [UserController::class, 'edit'])->name('user-edit');
Route::post('/user/edit/{gjid}', [UserController::class, 'update'])->name('user-update');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;

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
Route::post('/skin/create', [SkinController::class, 'store'])->name('skin-store');
Route::get('/skin/delete', [SkinController::class, 'destroy'])->name('skin-destroy');

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user-edit');
Route::post('/user/edit/{id}', [UserController::class, 'update'])->name('user-update');
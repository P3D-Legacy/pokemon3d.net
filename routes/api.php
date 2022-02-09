<?php

use App\Http\Controllers\API\v1\BanReasonController;
use App\Http\Controllers\API\v1\DiscordAccountController;
use App\Http\Controllers\API\v1\GamejoltAccountBanController;
use App\Http\Controllers\API\v1\GamejoltAccountController;
use App\Http\Controllers\API\v1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('gamejoltaccount', GamejoltAccountController::class);
    Route::apiResource(
        'ban/gamejoltaccount',
        GamejoltAccountBanController::class
    );
    Route::apiResource('banreason', BanReasonController::class);
    Route::apiResource('discordaccount', DiscordAccountController::class);
});

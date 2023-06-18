<?php

use App\Http\Controllers\API\v1\BanReasonController;
use App\Http\Controllers\API\v1\DiscordAccountController;
use App\Http\Controllers\API\v1\DiscordBotSettingController;
use App\Http\Controllers\API\v1\Game\BadgeController;
use App\Http\Controllers\API\v1\GamejoltAccountBanController;
use App\Http\Controllers\API\v1\GamejoltAccountController;
use App\Http\Controllers\API\v1\OpenAPIController;
use App\Http\Controllers\API\v1\PostController;
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
    Route::apiResource('user', UserController::class)->only('show');
    Route::apiResource('gamejoltaccount', GamejoltAccountController::class)->only('show');
    Route::apiResource('ban/gamejoltaccount', GamejoltAccountBanController::class)->only(['index', 'show', 'destroy']);
    Route::apiResource('banreason', BanReasonController::class)->only(['index', 'show']);
    Route::apiResource('discordaccount', DiscordAccountController::class)->only('show');
    Route::apiResource('bot/discord/settings', DiscordBotSettingController::class)->only(['index', 'update']);
    Route::apiResource('game/badges', BadgeController::class)->only('index');
    Route::apiResource('post', PostController::class)->only('post');
})->middleware(['api']);

Route::apiResource('openapi-json', OpenAPIController::class)->only('index');

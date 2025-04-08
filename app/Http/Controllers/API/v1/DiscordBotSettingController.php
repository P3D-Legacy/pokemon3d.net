<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Controller;
use App\Models\DiscordBotSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Discord Bot
 *
 * APIs for usage towards the Discord bot.
 */
class DiscordBotSettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:discord_bot_setting.show', only: ['index']),
            new Middleware('permission:discord_bot_setting.update', only: ['update']),
        ];
    }

    /**
     * Display the first resource.
     *
     * @jsonresponse {
     *    "data": [
     *        {
     *            "category_id": 1,
     *            "chat_id": 1,
     *            "events_id": 1,
     *            "hide_events": "{}",
     *            "created_at": "2021-01-01T17:57:10.000000Z",
     *            "updated_at": "2021-01-01T17:57:10.000000Z",
     *        },
     *    ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $discordBotSetting = DiscordBotSetting::first(); // Only show first in table

        return response()->json($discordBotSetting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @urlParam id int required The ID of the entry in the database.
     *
     * @bodyParam category_id int required The ID of your desired category channel.
     * @bodyParam chat_id int required The ID of your desired chat channel.
     * @bodyParam events_id int required The ID of your desired event channel.
     * @bodyParam hide_events json A JSON object.
     *
     * @jsonresponse 201 {
     *      "category_id": 1,
     *      "chat_id": 1,
     *      "events_id": 1,
     *      "hide_events": "{}",
     *      "created_at": "2021-01-01T17:57:10.000000Z",
     *      "updated_at": "2021-01-01T17:57:10.000000Z",
     * }
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if ($id !== 1) {
            return response()->json([
                'error' => 'Invalid ID!',
            ]);
        }
        $request->validate([
            'category_id' => 'required|integer',
            'chat_id' => 'required|integer',
            'events_id' => 'required|integer',
            'hide_events' => 'json|nullable',
        ]);
        $discordBotSetting = DiscordBotSetting::findOrFail($id);
        $discordBotSetting->update($request->all());

        return response()->json($discordBotSetting);
    }
}

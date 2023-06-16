<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\DiscordBotSetting;
use Illuminate\Http\Request;

/**
 * @group Discord Bot
 *
 * APIs for usage towards the Discord bot.
 */
class DiscordBotSettingController extends Controller
{
    /**
     * Display the first resource.
     *
     * @response {
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
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        if (! $request->user()) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        if (! $request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
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
     * @response 201 {
     *      "category_id": 1,
     *      "chat_id": 1,
     *      "events_id": 1,
     *      "hide_events": "{}",
     *      "created_at": "2021-01-01T17:57:10.000000Z",
     *      "updated_at": "2021-01-01T17:57:10.000000Z",
     * }
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        if (! $request->user()->tokenCan('update')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
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

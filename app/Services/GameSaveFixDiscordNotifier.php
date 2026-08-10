<?php

namespace App\Services;

use App\Enums\GameSaveFixRequestStatus;
use App\Filament\Resources\GameSaveFixRequestResource;
use App\Models\GameSaveFixRequest;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GameSaveFixDiscordNotifier
{
    public function created(GameSaveFixRequest $request): void
    {
        $this->send($request, 'New save fix request', hexdec('3B82F6'));
    }

    public function assigned(GameSaveFixRequest $request): void
    {
        $this->send($request, 'Save fix request assigned', hexdec('8B5CF6'));
    }

    public function statusChanged(GameSaveFixRequest $request, GameSaveFixRequestStatus $previousStatus): void
    {
        $this->send(
            $request,
            'Save fix request status changed',
            hexdec('F59E0B'),
            'Previous status: '.$previousStatus->label(),
        );
    }

    public function stale(GameSaveFixRequest $request): void
    {
        $this->send($request, 'Save fix request is stale', hexdec('EF4444'), 'No activity for at least 7 days.');
    }

    private function send(
        GameSaveFixRequest $request,
        string $title,
        int $color,
        ?string $extraDescription = null,
    ): void {
        $webhook = config('game-save.discord_webhook');

        if (! filled($webhook)) {
            return;
        }

        $request->loadMissing(['user', 'assignee']);

        $description = Str::limit($request->description, 300);
        if ($extraDescription) {
            $description = $extraDescription."\n\n".$description;
        }

        $url = GameSaveFixRequestResource::getUrl('view', ['record' => $request]);

        $payload = [
            'content' => $title,
            'tts' => false,
            'embeds' => [
                [
                    'title' => $title,
                    'type' => 'rich',
                    'description' => $description,
                    'url' => $url,
                    'timestamp' => now()->toIso8601String(),
                    'color' => $color,
                    'fields' => [
                        [
                            'name' => 'Requester',
                            'value' => $request->user?->username ?? 'Unknown',
                            'inline' => true,
                        ],
                        [
                            'name' => 'Status',
                            'value' => $request->status->label(),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Assignee',
                            'value' => $request->assignee?->username ?? 'Unassigned',
                            'inline' => true,
                        ],
                    ],
                ],
            ],
        ];

        try {
            Http::timeout(5)->post($webhook, $payload);
        } catch (Exception) {
            // Webhook failure must not block the request workflow.
        }
    }
}

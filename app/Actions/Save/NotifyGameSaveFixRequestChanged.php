<?php

namespace App\Actions\Save;

use App\Enums\GameSaveFixRequestStatus;
use App\Models\GameSaveFixRequest;
use App\Models\User;
use App\Notifications\Save\GameSaveFixRequestAssignedNotification;
use App\Notifications\Save\GameSaveFixRequestStaffStatusNotification;
use App\Notifications\Save\GameSaveFixRequestStatusNotification;
use App\Services\GameSaveFixDiscordNotifier;

class NotifyGameSaveFixRequestChanged
{
    public function __construct(private GameSaveFixDiscordNotifier $discord) {}

    public function created(GameSaveFixRequest $request): void
    {
        $this->discord->created($request);
    }

    public function claimed(GameSaveFixRequest $request, GameSaveFixRequestStatus $previousStatus): void
    {
        $this->discord->assigned($request);
        $this->notifyRequester($request, $previousStatus);
        $this->notifyAssigneeAssigned($request);
    }

    public function statusChanged(
        GameSaveFixRequest $request,
        GameSaveFixRequestStatus $previousStatus,
        bool $notifyRequester = true,
    ): void {
        $this->discord->statusChanged($request, $previousStatus);

        if ($notifyRequester) {
            $this->notifyRequester($request, $previousStatus);
        }

        $this->notifyAssigneeStatusChanged($request, $previousStatus);
    }

    public function stale(GameSaveFixRequest $request): void
    {
        $this->discord->stale($request);
        $request->markStaleNotified();
    }

    private function notifyRequester(
        GameSaveFixRequest $request,
        GameSaveFixRequestStatus $previousStatus,
    ): void {
        $request->loadMissing('user');

        if (! $request->user) {
            return;
        }

        if (! $request->notify_database && ! $request->notify_mail) {
            return;
        }

        $request->user->notify(new GameSaveFixRequestStatusNotification($request, $previousStatus));
    }

    private function notifyAssigneeAssigned(GameSaveFixRequest $request): void
    {
        $assignee = $this->assignee($request);

        if (! $assignee) {
            return;
        }

        $assignee->notify(new GameSaveFixRequestAssignedNotification($request));
    }

    private function notifyAssigneeStatusChanged(
        GameSaveFixRequest $request,
        GameSaveFixRequestStatus $previousStatus,
    ): void {
        $assignee = $this->assignee($request);

        if (! $assignee) {
            return;
        }

        $assignee->notify(new GameSaveFixRequestStaffStatusNotification($request, $previousStatus));
    }

    private function assignee(GameSaveFixRequest $request): ?User
    {
        $request->loadMissing('assignee');

        return $request->assignee;
    }
}

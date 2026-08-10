<?php

namespace App\Actions\Save;

use App\Enums\GameSaveFixRequestStatus;
use App\Models\GameSaveFixRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ManageGameSaveFixRequest
{
    public function __construct(private NotifyGameSaveFixRequestChanged $notifier) {}

    /**
     * @param  array{description: string, notify_database?: bool, notify_mail?: bool}  $data
     */
    public function create(User $user, array $data): GameSaveFixRequest
    {
        if ($user->gameSaveFixRequests()->active()->exists()) {
            throw ValidationException::withMessages([
                'description' => trans('You already have an open save fix request.'),
            ]);
        }

        $request = $user->gameSaveFixRequests()->create([
            'description' => $data['description'],
            'status' => GameSaveFixRequestStatus::Open,
            'consent_accepted_at' => now(),
            'consent_text' => config('game-save.fix_request_consent_text'),
            'notify_database' => $data['notify_database'] ?? true,
            'notify_mail' => $data['notify_mail'] ?? true,
        ]);

        $this->notifier->created($request);

        return $request;
    }

    public function claim(GameSaveFixRequest $request, User $staff): void
    {
        if ($request->status !== GameSaveFixRequestStatus::Open) {
            throw ValidationException::withMessages([
                'status' => trans('Only open requests can be claimed.'),
            ]);
        }

        $previousStatus = $request->status;

        $request->update([
            'assignee_id' => $staff->id,
            'status' => GameSaveFixRequestStatus::Claimed,
            'stale_notified_at' => null,
        ]);

        $this->notifier->claimed($request->fresh(['user', 'assignee']), $previousStatus);
    }

    public function resolve(GameSaveFixRequest $request): void
    {
        if ($request->status !== GameSaveFixRequestStatus::Claimed) {
            throw ValidationException::withMessages([
                'status' => trans('Only claimed requests can be resolved.'),
            ]);
        }

        $previousStatus = $request->status;

        $request->update([
            'status' => GameSaveFixRequestStatus::Resolved,
            'resolved_at' => now(),
            'stale_notified_at' => null,
        ]);

        $this->notifier->statusChanged($request->fresh(['user', 'assignee']), $previousStatus);
    }

    public function cancel(GameSaveFixRequest $request, bool $byRequester = false): void
    {
        if (! $request->status->isOpenOrClaimed()) {
            throw ValidationException::withMessages([
                'status' => trans('This request can no longer be cancelled.'),
            ]);
        }

        $previousStatus = $request->status;

        $request->update([
            'status' => GameSaveFixRequestStatus::Cancelled,
            'stale_notified_at' => null,
        ]);

        $this->notifier->statusChanged(
            $request->fresh(['user', 'assignee']),
            $previousStatus,
            notifyRequester: ! $byRequester,
        );
    }

    /**
     * @param  array{notify_database: bool, notify_mail: bool}  $data
     */
    public function updateNotificationPreferences(GameSaveFixRequest $request, array $data): void
    {
        $request->update([
            'notify_database' => $data['notify_database'],
            'notify_mail' => $data['notify_mail'],
        ]);
    }
}

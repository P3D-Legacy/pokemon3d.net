<?php

namespace App\Notifications\Save;

use App\Enums\GameSaveFixRequestStatus;
use App\Filament\Resources\GameSaveFixRequestResource;
use App\Models\GameSaveFixRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GameSaveFixRequestStaffStatusNotification extends Notification
{
    use Queueable;

    private string $message;

    private string $icon;

    public function __construct(
        public GameSaveFixRequest $request,
        public GameSaveFixRequestStatus $previousStatus,
    ) {
        $this->message = trans('A save fix request you are assigned to changed from :previous to :status.', [
            'previous' => $previousStatus->label(),
            'status' => $request->status->label(),
        ]);
        $this->icon =
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>';
    }

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('Save fix request status update'))
            ->line($this->message)
            ->action(
                trans('Open in admin'),
                GameSaveFixRequestResource::getUrl('view', ['record' => $this->request]),
            );
    }

    /**
     * @return array{message: string, url: string, icon: string}
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'message' => e($this->message),
            'url' => GameSaveFixRequestResource::getUrl('view', ['record' => $this->request]),
            'icon' => $this->icon,
        ];
    }
}

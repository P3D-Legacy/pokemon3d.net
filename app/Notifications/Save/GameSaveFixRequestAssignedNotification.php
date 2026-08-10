<?php

namespace App\Notifications\Save;

use App\Filament\Resources\GameSaveFixRequestResource;
use App\Models\GameSaveFixRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class GameSaveFixRequestAssignedNotification extends Notification
{
    use Queueable;

    private string $message;

    private string $icon;

    public function __construct(public GameSaveFixRequest $request)
    {
        $this->message = trans('You have been assigned a save fix request from :username.', [
            'username' => $request->user?->username ?? 'a user',
        ]);
        $this->icon =
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';
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
            ->subject(trans('Save fix request assigned to you'))
            ->line($this->message)
            ->line(Str::limit($this->request->description, 200))
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

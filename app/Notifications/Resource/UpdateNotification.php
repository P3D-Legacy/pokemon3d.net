<?php

namespace App\Notifications\Resource;

use App\Models\Resource;
use App\Models\ResourceUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateNotification extends Notification
{
    use Queueable;

    private Resource $resource;

    private ResourceUpdate $resourceUpdate;

    private string $message;

    private string $icon;

    public function __construct(Resource $resource, ResourceUpdate $resourceUpdate)
    {
        $this->resource = $resource;
        $this->resourceUpdate = $resourceUpdate;
        $this->message = trans(':title has a new update :version', [
            'title' => '<a class="text-green-400 no-underline hover:underline" href="'.
                route('resource.uuid', $this->resource->uuid).
                '">'.
                $this->resource->name.
                '</a>',
            'version' => e($this->resourceUpdate->title),
        ]);
        $this->icon =
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>';
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return $notifiable->hasGivenConsent('email.notifications') ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->message)
            ->action('View', route('resource.uuid', $this->resource->uuid));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array{message: string, url: string, icon: string}
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'message' => $this->message,
            'url' => route('resource.uuid', $this->resource->uuid),
            'icon' => $this->icon,
        ];
    }
}

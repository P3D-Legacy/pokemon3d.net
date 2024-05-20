<?php

namespace App\Notifications;

use App\Models\GameVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewGameUpdateNotification extends Notification
{
    use Queueable;

    private string $message;

    private string $icon;

    private GameVersion $gameVersion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($gameVersion)
    {
        $this->gameVersion = $gameVersion;
        $this->message = 'We have updated the game!';
        $this->icon =
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>';
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return $notifiable->hasGivenConsent('email.newsletter') ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $btn_text = $this->gameVersion->post ? 'View blog post' : 'View changelog';

        return (new MailMessage())
            ->line($this->message)
            ->line($this->gameVersion->title)
            ->action(
                $btn_text,
                $this->gameVersion->post
                    ? route('blog.show', $this->gameVersion->post->uuid)
                    : url($this->gameVersion->page_url)
            );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'message' => $this->message,
            'url' => $this->gameVersion->post
                ? route('blog.show', $this->gameVersion->post->uuid)
                : url($this->gameVersion->page_url),
            'icon' => $this->icon,
        ];
    }
}

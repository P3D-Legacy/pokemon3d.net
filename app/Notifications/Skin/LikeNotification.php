<?php

namespace App\Notifications\Skin;

use App\Models\Skin;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification
{
    use Queueable;

    private Skin $skin;

    private string $message;

    private string $icon;

    private User $liker;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($skin, $liker)
    {
        $this->skin = $skin;
        $this->liker = $liker;
        $this->message = trans(':username liked your skin :title', [
            'username' => '<a class="text-green-400 no-underline hover:underline" href="'.
                route('member.show', $this->liker->username).
                '">'.
                $this->liker->username.
                '</a>',
            'title' => '<a class="text-green-400 no-underline hover:underline" href="'.
                route('skin-show', $this->skin->uuid).
                '">'.
                $this->skin->name.
                '</a>',
        ]);
        $this->icon =
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->hasGivenConsent('email.notifications') ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->line($this->message)->action('View', route('skin-show', $this->skin->uuid));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => route('skin-show', $this->skin->uuid),
            'icon' => $this->icon,
        ];
    }
}

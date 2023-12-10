<?php

namespace App\Notifications\Post;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification
{
    use Queueable;

    private Comment $comment;

    private string $message;

    private string $icon;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
        $this->message = trans(':username replied to your comment on :title', [
            'username' => '<a class="text-green-400 no-underline hover:underline" href="'.
                route('member.show', $this->comment->creator).
                '">'.
                $this->comment->creator->username.
                '</a>',
            'title' => '<a class="text-green-400 no-underline hover:underline" href="'.
                route('blog.show', $this->comment->commentable->uuid).
                '">'.
                $this->comment->commentable->title.
                '</a>',
        ]);
        $this->icon =
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return $notifiable->hasGivenConsent('email.notifications') ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->line($this->message)
            ->action('View', route('blog.show', $this->comment->commentable->uuid));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'message' => $this->message,
            'url' => route('blog.show', $this->comment->commentable->uuid),
            'icon' => $this->icon,
        ];
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\User;
use Auth;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Livewire\Component;

class Notifications extends Component
{
    public DatabaseNotificationCollection $unreadNotifications;
    public User $user;
    public int $count;
    public int $max = 5;

    protected $listeners = [
        'notificationDismissed' => 'update',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->unreadNotifications = $this->user->unreadNotifications->take($this->max);
        $this->count = $this->user->unreadNotifications->count();
    }

    public function update()
    {
        $this->unreadNotifications = $this->user->unreadNotifications->take($this->max);
        $this->count = $this->user->unreadNotifications->count();
    }

    public function open($id)
    {
        $notification = $this->user->notifications->find($id);
        $this->dismiss($id);
        return redirect()->to($notification->data['url']);
    }

    public function dismiss($id)
    {
        $this->user->notifications->find($id)->markAsRead();
        $this->emit('notificationsUpdated');
    }

    public function dismissAll()
    {
        $this->user->unreadNotifications->markAsRead();
        $this->emit('notificationsUpdated');
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    private $unreadNotifications;

    public function mount()
    {
        $this->unreadNotifications = auth()->user()->unreadNotifications;
    }

    public function open($id)
    {
        $notification = auth()
            ->user()
            ->notifications->find($id);

        $notification->markAsRead();

        return redirect($notification->data['url']);
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}

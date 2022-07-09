<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    protected $listeners = [
        'notificationDismissed' => '$refresh',
    ];

    public function open($id)
    {
        $notification = Auth::user()->notifications->find($id);
        $this->dismiss($id);

        return redirect()->to($notification->data['url']);
    }

    public function dismiss($id)
    {
        Auth::user()
            ->notifications->find($id)
            ->markAsRead();
        $this->emit('notificationsUpdated');
    }

    public function dismissAll()
    {
        Auth::user()->notifications->markAsRead();
        $this->emit('notificationsUpdated');
    }

    public function render()
    {
        return view('livewire.notification-list', [
            'notifications' => Auth::user()
                ->notifications()
                ->paginate(),
        ]);
    }
}

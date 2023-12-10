<?php

namespace App\Http\Livewire;

use App\Models\User;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Notifications extends Component
{
    public Collection $unreadNotifications;

    public User $user;

    public int $count;

    public int $max = 5;

    protected $listeners = [
        'notificationsUpdated' => 'update',
    ];

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->unreadNotifications = $this->user->unreadNotifications->take($this->max);
        $this->count = $this->user->unreadNotifications->count();
    }

    public function update(): void
    {
        $this->unreadNotifications = $this->user->unreadNotifications->take($this->max);
        $this->count = $this->user->unreadNotifications->count();
    }

    public function open($id): RedirectResponse
    {
        $notification = $this->user->notifications->find($id);
        $this->dismiss($id);

        return redirect()->to($notification->data['url']);
    }

    public function dismiss($id): void
    {
        $this->user->notifications->find($id)->markAsRead();
        $this->emit('notificationsUpdated');
    }

    public function dismissAll(): void
    {
        $this->user->unreadNotifications->markAsRead();
        $this->emit('notificationsUpdated');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.notifications');
    }
}

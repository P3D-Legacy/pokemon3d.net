<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * Display the user's notifications.
     */
    public function index(Request $request): Response
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate()
            ->through(fn ($notification): array => [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? '',
                'icon' => $notification->data['icon'] ?? null,
                'url' => $notification->data['url'] ?? null,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_for_humans' => $notification->created_at->diffForHumans(),
            ]);

        return Inertia::render('notifications/index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a notification as read and open its target URL.
     */
    public function open(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if (! is_string($url) || ! str_starts_with($url, '/') || str_starts_with($url, '//')) {
            return redirect()->route('notifications.index');
        }

        return redirect()->to($url);
    }

    /**
     * Mark a notification as read.
     */
    public function dismiss(Request $request, string $id): RedirectResponse
    {
        $request->user()->notifications()->findOrFail($id)->markAsRead();

        return back();
    }

    /**
     * Mark all notifications as read.
     */
    public function dismissAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}

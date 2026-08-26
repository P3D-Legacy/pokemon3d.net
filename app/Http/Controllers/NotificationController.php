<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Return the user's latest notifications as JSON.
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($notification): array => $this->transform($notification))
            ->values();

        return response()->json([
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

        $url = $this->safeRedirectUrl($notification->data['url'] ?? null);

        if ($url === null) {
            return back();
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

    /**
     * @param  DatabaseNotification  $notification
     * @return array<string, mixed>
     */
    private function transform(mixed $notification): array
    {
        return [
            'id' => $notification->id,
            'message' => strip_tags($notification->data['message'] ?? ''),
            'icon' => $notification->data['icon'] ?? null,
            'url' => $notification->data['url'] ?? null,
            'read_at' => $notification->read_at?->toIso8601String(),
            'created_for_humans' => $notification->created_at->diffForHumans(),
        ];
    }

    private function safeRedirectUrl(mixed $url): ?string
    {
        if (! is_string($url) || $url === '') {
            return null;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $targetHost = parse_url($url, PHP_URL_HOST);
        $targetScheme = parse_url($url, PHP_URL_SCHEME);

        if (
            is_string($appHost)
            && is_string($targetHost)
            && is_string($targetScheme)
            && in_array(strtolower($targetScheme), ['http', 'https'], true)
            && strcasecmp($appHost, $targetHost) === 0
        ) {
            return $url;
        }

        return null;
    }
}

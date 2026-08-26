<?php

namespace App\Http\Controllers\Save;

use App\Actions\Save\ManageGameSaveFixRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Save\StoreGameSaveFixRequestRequest;
use App\Http\Requests\Save\UpdateGameSaveFixRequestNotificationsRequest;
use App\Models\GameSaveFixRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameSaveFixRequestController extends Controller
{
    public function index(Request $request): Response
    {
        $requests = $request->user()
            ->gameSaveFixRequests()
            ->latest()
            ->paginate(10)
            ->through(fn (GameSaveFixRequest $fixRequest): array => $this->transform($fixRequest));

        return Inertia::render('save/fix-requests/index', [
            'requests' => $requests,
            'canCreate' => ! $request->user()->gameSaveFixRequests()->active()->exists(),
            'consentText' => config('game-save.fix_request_consent_text'),
        ]);
    }

    public function create(Request $request): Response|RedirectResponse
    {
        if ($request->user()->gameSaveFixRequests()->active()->exists()) {
            return redirect()
                ->route('save.fix-requests.index')
                ->with('flash.banner', trans('You already have an open save fix request.'))
                ->with('flash.bannerStyle', 'warning');
        }

        return Inertia::render('save/fix-requests/create', [
            'consentText' => config('game-save.fix_request_consent_text'),
        ]);
    }

    public function store(
        StoreGameSaveFixRequestRequest $request,
        ManageGameSaveFixRequest $manager,
    ): RedirectResponse {
        $fixRequest = $manager->create($request->user(), $request->gameSaveFixRequestData());

        return redirect()
            ->route('save.fix-requests.show', $fixRequest)
            ->with('flash.banner', trans('Your save fix request has been submitted.'));
    }

    public function show(Request $request, GameSaveFixRequest $fixRequest): Response
    {
        abort_unless($request->user()->is($fixRequest->user), 403);

        return Inertia::render('save/fix-requests/show', [
            'fixRequest' => $this->transform($fixRequest->load('assignee')),
            'consentText' => $fixRequest->consent_text,
        ]);
    }

    public function updateNotifications(
        UpdateGameSaveFixRequestNotificationsRequest $request,
        GameSaveFixRequest $fixRequest,
        ManageGameSaveFixRequest $manager,
    ): RedirectResponse {
        $manager->updateNotificationPreferences($fixRequest, $request->notificationPreferences());

        return back()->with('flash.banner', trans('Notification preferences updated.'));
    }

    public function cancel(
        Request $request,
        GameSaveFixRequest $fixRequest,
        ManageGameSaveFixRequest $manager,
    ): RedirectResponse {
        abort_unless($request->user()->is($fixRequest->user), 403);

        $manager->cancel($fixRequest, byRequester: true);

        return redirect()
            ->route('save.fix-requests.show', $fixRequest)
            ->with('flash.banner', trans('Your save fix request has been cancelled.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function transform(GameSaveFixRequest $fixRequest): array
    {
        return [
            'uuid' => $fixRequest->uuid,
            'description' => $fixRequest->description,
            'status' => $fixRequest->status->value,
            'status_label' => $fixRequest->status->label(),
            'assignee' => $fixRequest->assignee ? [
                'username' => $fixRequest->assignee->username,
            ] : null,
            'notify_database' => $fixRequest->notify_database,
            'notify_mail' => $fixRequest->notify_mail,
            'consent_accepted_at' => $fixRequest->consent_accepted_at?->toIso8601String(),
            'resolved_at' => $fixRequest->resolved_at?->toIso8601String(),
            'created_at' => $fixRequest->created_at?->toIso8601String(),
            'updated_at' => $fixRequest->updated_at?->toIso8601String(),
            'can_cancel' => $fixRequest->status->isOpenOrClaimed(),
        ];
    }
}

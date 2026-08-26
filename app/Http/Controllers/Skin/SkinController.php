<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use App\Notifications\Skin\LikeNotification;
use App\Support\SkinPresenter;
use App\Support\SkinStorage;
use ByteUnits\Binary;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;
use League\Flysystem\UnableToReadFile;
use Throwable;

class SkinController extends Controller
{
    public function show(Skin $skin): Response
    {
        $skin = Skin::query()
            ->where('uuid', $skin->uuid)
            ->isPublic()
            ->with('user')
            ->withCount('likers')
            ->first();

        abort_unless($skin, 404);

        $user = Auth::user();
        if ($user) {
            $user->attachLikeStatus($skin);
        }

        return Inertia::render('skins/show', [
            'skin' => SkinPresenter::card($skin, $user),
        ]);
    }

    public function newestpublicskins(Request $request): Response
    {
        return $this->publicIndex($request, 'newest');
    }

    public function popularpublicskins(Request $request): Response
    {
        return $this->publicIndex($request, 'popular');
    }

    public function create(Request $request): Response|RedirectResponse
    {
        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();

        if ($skincount >= config('skins.max_upload')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        return Inertia::render('skins/create', [
            'slots' => [
                'used' => $skincount,
                'max' => (int) config('skins.max_upload'),
            ],
            'width' => (int) config('skins.width'),
            'height' => (int) config('skins.height'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $gju = Auth::user()->gamejolt->username;
        $gjau = $request->session()->get('gjau');

        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();

        if ($skincount >= config('skins.max_upload')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        $request->validate([
            'image' => SkinStorage::imageValidationRules(),
            'name' => ['required', 'string', 'max:48'],
            'public' => [''],
            'rules' => ['accepted'],
        ]);

        $public = $request->boolean('public');
        $name = $request->string('name')->toString();

        $skin = Skin::create([
            'owner_id' => $gjid,
            'user_id' => Auth::id(),
            'public' => $public,
            'name' => $name,
        ]);

        try {
            SkinStorage::storeLibrary($request->file('image'), $skin->uuid);
        } catch (Throwable $exception) {
            report($exception);
            $skin->forceDelete();

            return redirect()
                ->route('skin-create')
                ->with('error', 'Could not store the skin file. Please try again.')
                ->with('flash.banner', 'Could not store the skin file. Please try again.')
                ->with('flash.bannerStyle', 'danger');
        }

        $webhook = config('skins.discord_webhook');
        if ($webhook && $public) {
            $this->notifyDiscordWebhook($webhook, $skin, $name, $gju, $gjau);
        }

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');
    }

    public function apply(Request $request, string $uuid): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $skin = Skin::query()->where('uuid', $uuid)->first();
        abort_unless($skin, 404);
        abort_unless($this->viewerCanAccessSkin($skin), 403);

        try {
            SkinStorage::copyLibraryToPlayer($skin->uuid, $gjid);
        } catch (UnableToReadFile|Exception) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'Could not apply skin.');
        }

        return redirect()
            ->route('skin-home')
            ->with('success', 'Skin was applied! Not seeing it? Refresh the page again.');
    }

    public function like(Request $request, string $uuid): RedirectResponse
    {
        $user = Auth::user();
        $skin = Skin::query()->where('uuid', $uuid)->first();
        abort_unless($skin, 404);
        abort_unless($this->viewerCanAccessSkin($skin), 403);

        if ($user->gamejolt->id != $skin->owner_id || config('app.debug')) {
            $user->toggleLike($skin);
            if ($user->hasLiked($skin) && $skin->user) {
                Notification::send($skin->user, new LikeNotification($skin, $user));
            }
        }

        return redirect()->back();
    }

    public function edit(Request $request, string $uuid): Response|RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $skin = Skin::query()->where('uuid', $uuid)->first();
        abort_unless($skin, 404);

        if ($gjid != $skin->owner_id) {
            return redirect()
                ->route('skins')
                ->with('error', 'You do not own this skin!');
        }

        return Inertia::render('skins/edit', [
            'skin' => [
                'uuid' => $skin->uuid,
                'name' => $skin->name,
                'public' => (bool) $skin->public,
                'image_url' => SkinStorage::urlLibrary(
                    $skin->uuid,
                    $skin->updated_at?->timestamp ?? now()->timestamp
                ),
            ],
        ]);
    }

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $skin = Skin::query()->where('uuid', $uuid)->first();
        abort_unless($skin, 404);

        if ($gjid != $skin->owner_id) {
            return redirect()
                ->route('skins')
                ->with('error', 'You do not own this skin!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:48'],
            'public' => [''],
        ]);

        $skin->update([
            'public' => $request->boolean('public'),
            'name' => $request->string('name')->toString(),
        ]);

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was updated!');
    }

    public function destroy(Request $request, string $uuid): RedirectResponse
    {
        try {
            $gjid = $request->user()->gamejolt->id;
            $skin = Skin::query()->where('uuid', $uuid)->first();
            abort_unless($skin, 404);

            if ($gjid != $skin->owner_id) {
                session()->flash('flash.bannerStyle', 'warning');
                session()->flash('flash.banner', 'You do not own this skin!');

                return redirect()->route('skins-my');
            }

            SkinStorage::deleteLibrary($skin->uuid);
            $skin->delete();

            session()->flash('flash.bannerStyle', 'success');
            session()->flash('flash.banner', 'Skin was deleted!');

            return redirect()->route('skins-my');
        } catch (Exception) {
            session()->flash('flash.bannerStyle', 'danger');
            session()->flash('flash.banner', 'Something went wrong!');

            return redirect()->route('skins-my');
        }
    }

    private function publicIndex(Request $request, string $sort): Response
    {
        $user = $request->user();
        $query = Skin::query()
            ->isPublic()
            ->with('user')
            ->withCount('likers');

        if ($sort === 'popular') {
            $query->orderByDesc('likers_count');
        } else {
            $query->orderByDesc('created_at');
        }

        $skins = $query->paginate(9);

        if ($user) {
            $user->attachLikeStatus($skins);
        }

        return Inertia::render('skins/public/index', [
            'skins' => $skins->through(fn (Skin $skin): array => SkinPresenter::card($skin, $user)),
            'sort' => $sort,
        ]);
    }

    private function viewerCanAccessSkin(Skin $skin): bool
    {
        if ($skin->public) {
            return true;
        }

        $viewerGamejoltId = Auth::user()?->gamejolt?->id;

        return $viewerGamejoltId !== null && (int) $viewerGamejoltId === (int) $skin->owner_id;
    }

    private function notifyDiscordWebhook(
        string $webhookurl,
        Skin $skin,
        string $name,
        string $gju,
        mixed $gjau,
    ): void {
        $payload = [
            'content' => $gju.
                ' uploaded a new skin for the public to use! Check it out here: '.
                route('skin-show', $skin->uuid),
            'tts' => false,
            'embeds' => [
                [
                    'title' => $name,
                    'type' => 'rich',
                    'description' => 'File size: '.(($size = SkinStorage::sizeLibrary($skin->uuid)) !== null
                        ? Binary::bytes($size)->format()
                        : 'N/A'),
                    'url' => route('skin-show', $skin->uuid),
                    'timestamp' => Carbon::now()->toIso8601String(),
                    'color' => hexdec('198754'),
                    'footer' => [
                        'text' => $gju,
                        'icon_url' => $gjau,
                    ],
                    'thumbnail' => [
                        'url' => $skin->urlPath(),
                    ],
                    'author' => [
                        'name' => $gju.' uploaded a skin',
                    ],
                ],
            ],
        ];

        try {
            Http::timeout(5)->post($webhookurl, $payload);
        } catch (Exception) {
            // Webhook failure must not block the upload response.
        }
    }
}

<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use App\Notifications\Skin\LikeNotification;
use App\Support\SkinPresenter;
use ByteUnits\Binary;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use League\Flysystem\UnableToReadFile;

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
        $user = $request->user();
        $skins = Skin::query()
            ->isPublic()
            ->with('user')
            ->withCount('likers')
            ->orderByDesc('created_at')
            ->paginate(9);

        if ($user) {
            $user->attachLikeStatus($skins);
        }

        return Inertia::render('skins/public/newest', [
            'skins' => $skins->through(fn (Skin $skin): array => SkinPresenter::card($skin, $user)),
        ]);
    }

    public function popularpublicskins(Request $request): Response
    {
        $user = $request->user();
        $skins = Skin::query()
            ->isPublic()
            ->with('user')
            ->withCount('likers')
            ->orderByDesc('likers_count')
            ->paginate(9);

        if ($user) {
            $user->attachLikeStatus($skins);
        }

        return Inertia::render('skins/public/popular', [
            'skins' => $skins->through(fn (Skin $skin): array => SkinPresenter::card($skin, $user)),
        ]);
    }

    public function create(Request $request): Response|RedirectResponse
    {
        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();

        if ($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        return Inertia::render('skins/create');
    }

    public function store(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $gju = Auth::user()->gamejolt->username;
        $gjau = $request->session()->get('gjau');

        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();

        if ($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'],
            'name' => ['required', 'string', 'max:48'],
            'public' => [''],
            'rules' => ['accepted'],
        ]);

        $public = $request->boolean('public');
        $name = $request->get('name');

        $skin = Skin::create([
            'owner_id' => $gjid,
            'user_id' => Auth::id(),
            'public' => $public,
            'name' => $name,
        ]);

        $filename = $skin->uuid.'.png';
        $request->file('image')->storeAs(null, $filename, 'skin');

        if (env('DISCORD_SKIN_UPLOAD_WEBHOOK') && $public) {
            $webhookurl = env('DISCORD_SKIN_UPLOAD_WEBHOOK');
            $json_data = json_encode(
                [
                    'content' => $gju.
                        ' uploaded a new skin for the public to use! Check it out here: '.
                        route('skin-show', $skin->uuid),
                    'tts' => false,
                    'embeds' => [
                        [
                            'title' => $name,
                            'type' => 'rich',
                            'description' => 'File size: '.Binary::bytes(Storage::disk('skin')->size($skin->path()))->format(),
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
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );
            $ch = curl_init($webhookurl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
        }

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');
    }

    public function apply(Request $request, string $uuid): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $filename = $gjid.'.png';
        $skin = Skin::where('uuid', $uuid)->first();
        abort_unless($skin, 404);

        try {
            Storage::disk('player')->put($filename, Storage::disk('skin')->get($skin->path()));
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
        $skin = Skin::where('uuid', $uuid)->first();
        abort_unless($skin, 404);

        if ($user->gamejolt->id != $skin->owner_id || config('app.debug')) {
            $user->toggleLike($skin);
            if ($user->hasLiked($skin) && $skin->user) {
                \Notification::send($skin->user, new LikeNotification($skin, $user));
            }
        }

        return redirect()->back();
    }

    public function edit(Request $request, string $uuid): Response|RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $skin = Skin::where('uuid', $uuid)->first();
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
            ],
        ]);
    }

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $skin = Skin::where('uuid', $uuid)->first();
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
            'name' => $request->get('name'),
        ]);

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was updated!');
    }

    public function destroy(Request $request, string $uuid): RedirectResponse
    {
        try {
            $gjid = $request->user()->gamejolt->id;
            $skin = Skin::where('uuid', $uuid)->first();
            abort_unless($skin, 404);

            if ($gjid != $skin->gamejoltaccount->id) {
                session()->flash('flash.bannerStyle', 'warning');
                session()->flash('flash.banner', 'You do not own this skin!');

                return redirect()->route('skins-my');
            }

            $filename = $skin->uuid.'.png';
            if (! Storage::disk('skin')->exists($filename)) {
                session()->flash('flash.bannerStyle', 'warning');
                session()->flash('flash.banner', 'Skin does not exist!');

                return redirect()->route('skins-my');
            }

            Storage::disk('skin')->delete($filename);
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
}

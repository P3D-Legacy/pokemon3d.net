<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use App\Notifications\Skin\LikeNotification;
use ByteUnits\Binary;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class SkinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Skin $skin)
    {
        $skin = Skin::where('uuid', $skin->uuid)
            ->isPublic()
            ->first();
        abort_unless($skin, 404);

        return view('skin.show')->with('skin', $skin);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newestpublicskins()
    {
        $skins = Skin::isPublic()
            ->orderBy('created_at', 'DESC')
            ->paginate(9);

        return view('skin.public.newest')->with('skins', $skins);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function popularpublicskins()
    {
        $skins = Skin::isPublic()
            ->withCount('likers')
            ->orderBy('likers_count', 'desc')
            ->paginate(9);

        return view('skin.public.popular')->with('skins', $skins);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();
        if ($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        return view('skin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'], // 2MB
            'name' => ['required', 'string', 'max:48'],
            'public' => [''],
            'rules' => ['accepted'],
        ]);

        $public = $request->boolean('public');
        $name = $request->get('name');

        $skin = Skin::create([
            'owner_id' => $gjid,
            'public' => $public,
            'name' => $name,
        ]);

        $filename = $skin->uuid.'.png';
        $request->file('image')->storeAs(null, $filename, 'skin');

        /*
         *
         * DISCORD WEBHOOK
         *
         */
        if (env('DISCORD_SKIN_UPLOAD_WEBHOOK') && $public) {
            $webhookurl = env('DISCORD_SKIN_UPLOAD_WEBHOOK');
            $json_data = json_encode(
                [
                    'content' => $gju.
                        ' uploaded a new skin for the public to use! Check it out here: '.
                        route('skin-show', $skin->uuid), // Message
                    // "username" => env('APP_NAME'), // Username (message posted as username) - NOTE: This should be set in the webhook with the avatar
                    'tts' => false, // Enable text-to-speech
                    // Embeds Array
                    'embeds' => [
                        [
                            'title' => $name, // Embed Title
                            'type' => 'rich', // Embed Type
                            'description' => 'File size: '.Binary::bytes(Storage::disk('skin')->size($skin->path()))->format(), // Embed Description
                            'url' => route('skin-show', $skin->uuid), // URL of title link
                            'timestamp' => Carbon::now()->toIso8601String(), // Timestamp of embed must be formatted as ISO8601
                            'color' => hexdec('198754'), // Embed left border color in HEX
                            // Footer
                            'footer' => [
                                'text' => $gju, // GJ Username
                                'icon_url' => $gjau, // GJ Avatar
                            ],
                            // Skin URL
                            'thumbnail' => [
                                'url' => $skin->urlPath(),
                            ],
                            // Author
                            'author' => [
                                'name' => $gju.' uploaded a skin', // GJ Username
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
            $response = curl_exec($ch);
            curl_close($ch);
        }
        /*
         *
         * END DISCORD WEBHOOK
         *
         */

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');
    }

    /**
     * Apply the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apply(Request $request, $uuid)
    {
        $gjid = Auth::user()->gamejolt->id;
        $filename = $gjid.'.png';
        $skin = Skin::where('uuid', $uuid)->first();
        try {
            Storage::disk('player')->put($filename, Storage::disk('skin')->get($skin->path()));
        } catch (FileNotFoundException $e) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'Could not apply skin.');
        }

        return redirect()
            ->route('skin-home')
            ->with('success', 'Skin was applied! Not seeing it? Refresh the page again.');
    }

    /**
     * Like the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request, $uuid)
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uuid)
    {
        $gjid = Auth::user()->gamejolt->id;
        $skin = Skin::where('uuid', $uuid)->first();
        if ($gjid != $skin->owner_id) {
            return redirect()
                ->route('skins')
                ->with('error', 'You do not own this skin!');
        }

        return view('skin.edit')->with('skin', $skin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $gjid = Auth::user()->gamejolt->id;
        $skin = Skin::where('uuid', $uuid)->first();
        if ($gjid != $skin->owner_id) {
            return redirect()
                ->route('skins')
                ->with('error', 'You do not own this skin!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:48'],
            'public' => [''],
        ]);

        $skin = Skin::where('uuid', $uuid)
            ->first()
            ->update([
                'public' => $request->boolean('public'),
                'name' => $request->get('name'),
            ]);

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param $uuid
     * @return RedirectResponse
     */
    public function destroy(Request $request, $uuid): RedirectResponse
    {
        try {
            $gjid = $request->user()->gamejolt->id;
            $skin = Skin::where('uuid', $uuid)->first();
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

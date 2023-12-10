<?php

namespace App\Http\Controllers\Skin;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Skin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlayerSkinController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['gj.admin'])->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $playerskins = array_filter(
            Storage::disk('player')->files(),
            function ($item) {
                return strpos($item, '.png');
            } // only png's
        );

        return view('player-skin.index')->with('playerskins', $playerskins);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'], // 2MB
            'rules' => ['accepted'],
        ]);
        $filename = $gjid.'.png';
        $request->file('image')->storeAs(null, $filename, 'player');

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');

        return redirect()->route('skin-home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();
        if ($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }
        $old_filename = $gjid.'.png';
        $skin = Skin::create([
            'owner_id' => $gjid,
            'user_id' => auth()->user()->id,
            'name' => 'Import: '.$gjid,
        ]);
        $new_filename = $skin->uuid.'.png';
        Storage::disk('skin')->put($new_filename, Storage::disk('player')->get($old_filename));

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was duplicated!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $filename = $gjid.'.png';
        if (! Storage::disk('player')->exists($filename)) {
            return redirect()
                ->route('skin-home')
                ->with('error', 'Skin was not found!');
        }
        Storage::disk('player')->delete($filename);

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was successfully deleted!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAsAdmin(Request $request, $gjid): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);
        $filename = $gjid.'.png';
        if (! Storage::disk('player')->exists($filename)) {
            return redirect()
                ->route('player-skins')
                ->with('error', 'Skin was not found!');
        }
        activity()
            ->causedBy(Auth::user()->gamejolt)
            ->withProperties([
                'filename' => $filename,
                'gjid' => $gjid,
                'reason' => $request->reason,
            ])
            ->log('deleted');
        Storage::disk('player')->delete($filename);

        return redirect()
            ->route('player-skins')
            ->with('success', 'Skin was successfully deleted!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Skin;
use App\Models\GJUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkinController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $skin = Skin::where('uuid', $uuid)->first();
        abort_unless($skin, 404);
        return view('skin.show')->with('skin', $skin);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicskins()
    {
        $skins = Skin::public()->get();
        return view('skin.public')->with('skins', $skins);
    }

    /**
     * Display a listing of the resource.
     *
     * * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function myskins(Request $request)
    {
        $gjid = $request->session()->get('gjid');
        $skins = GJUser::find($gjid)->skins()->get();
        //$skins = Skin::where('owner_id', $gjid)->get();
        return view('skin.my')->with('skins', $skins);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $gjid = $request->session()->get('gjid');

        $skincount = GJUser::find($gjid)->skins()->count();

        if($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()->route('skins')->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'], // 2MB
            'name' => ['required', 'string'],
            'public' => [''],
            'rules' => ['accepted'],
        ]);
        
        $skin = Skin::create([
            'owner_id' => $gjid,
            'public' => $request->boolean('public'),
            'name' => $request->get('name'),
        ]);

        $filename = $skin->uuid.'.png';
        $request->file('image')->storeAs(null, $filename, 'skin');

        return redirect()->route('skins-my')->with('success', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');
    }

    /**
     * Apply the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apply(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $filename = $gjid.'.png';
        $skin = Skin::where('uuid', $uuid)->first();
        Storage::disk('player')->put($filename, Storage::disk('skin')->get($skin->path()));
        return redirect()->route('home')->with('success', 'Skin was applied! Not seeing it? Refresh the page again.');
    }

    /**
     * Like the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $user = GJUser::find($gjid);
        $skin = Skin::where('uuid', $uuid)->first();
        abort_unless($skin, 404);
        if($user->gjid != $skin->owner_id) {
            $user->toggleLike($skin);
        }
        return redirect()->route('skins');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  str  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $skin = Skin::where('uuid', $uuid)->first();
        if($gjid != $skin->owner_id) {
            return redirect()->route('skins')->with('error', 'You do not own this skin!');
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
        $gjid = $request->session()->get('gjid');
        $skin = Skin::where('uuid', $uuid)->first();
        if($gjid != $skin->owner_id) {
            return redirect()->route('skins')->with('error', 'You do not own this skin!');
        }

        $request->validate([
            'name' => ['required', 'string'],
            'public' => [''],
        ]);
        
        $skin = Skin::where('uuid', $uuid)->first()->update([
            'public' => $request->boolean('public'),
            'name' => $request->get('name'),
        ]);

        return redirect()->route('skins-my')->with('success', 'Skin was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $skin = Skin::where('uuid', $uuid)->first();
        if($gjid != $skin->owner_id) {
            return redirect()->route('skins')->with('error', 'You do not own this skin!');
        }
        $filename = $skin->uuid.'.png';
        if(!Storage::disk('skin')->exists($filename)) {
            return redirect()->route('skins')->with('error', 'Skin was not found!');
        }
        Storage::disk('skin')->delete($filename);
        $skin->delete();
        return redirect()->route('skins-my')->with('success', 'Skin was successfully deleted!');
    }
}

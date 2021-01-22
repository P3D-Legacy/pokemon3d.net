<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
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
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gjid = $request->session()->get('gjid');

        //dd($request->file('image')->getMimeType());

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png'], // 2MB
            'rules' => ['accepted'],
        ]);

        $image = $request->file('image');

        $filename = $gjid . '.' . $image->getClientOriginalExtension();
        $path = public_path('/skins/' . $filename);
        try {
            Image::make($image->getRealPath())->save($path);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
        return redirect()->route('home')->with('success', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $gjid = $request->session()->get('gjid');
        $filename = $gjid.'.png';
        if(!Storage::disk('skins')->exists($filename)) {
            return redirect()->route('home')->with('error', 'Skin was not found!');
        }
        Storage::disk('skins')->delete($filename);
        return redirect()->route('home')->with('success', 'Skin was successfully deleted!');
    }
}

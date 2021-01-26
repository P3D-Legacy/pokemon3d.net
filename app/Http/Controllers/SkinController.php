<?php

namespace App\Http\Controllers;

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

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'], // 2MB
            'rules' => ['accepted'],
        ]);
        $filename = $gjid.'.png';
        $request->file('image')->storeAs(null, $filename, 'player');

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
        if(!Storage::disk('player')->exists($filename)) {
            return redirect()->route('home')->with('error', 'Skin was not found!');
        }
        Storage::disk('player')->delete($filename);
        return redirect()->route('home')->with('success', 'Skin was successfully deleted!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::orderBy('sticky', 'desc')->orderBy('created_at', 'desc')->where('active', true)->withAnyTags(['Website', 'Game'])->take(4)->get();
        return view('home')->with('posts', $posts);
    }
}

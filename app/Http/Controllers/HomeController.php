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
        $posts = Post::where("published_at", "<=", now())
            ->where("active", true)
            ->orderBy("sticky", "desc")
            ->orderBy("published_at", "desc")
            ->withAnyTags(["Website", "Game"])
            ->take(4)
            ->get();
        return view("home")->with("posts", $posts);
    }
}

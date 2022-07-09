<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Digikraaft\ReviewRating\Models\Review;
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
        $posts = Post::where('published_at', '<=', now())
            ->where('active', true)
            ->orderBy('sticky', 'desc')
            ->orderBy('published_at', 'desc')
            ->withAnyTags(['Website', 'Game'])
            ->take(4)
            ->get();
        $reviews = Review::where('model_type', '=', 'App\Models\GameVersion')
            ->orderBy('created_at', 'desc')
            ->get();
        $numberOfReviews = $reviews->count();
        $averageRating = round($reviews->pluck('rating')->avg(), 1);

        return view('home', compact('posts', 'reviews', 'averageRating', 'numberOfReviews'));
    }
}

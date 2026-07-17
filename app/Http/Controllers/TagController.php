<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:tag.create|tag.update|tag.destroy'], only: ['index']),
            new Middleware(['permission:tag.create'], only: ['create', 'store']),
            new Middleware(['permission:tag.update'], only: ['update', 'edit']),
            new Middleware(['permission:tag.destroy'], only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('mod/tags/index', [
            'tags' => Tag::query()
                ->orderByDesc('created_at')
                ->paginate(10)
                ->through(fn (Tag $tag): array => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('mod/tags/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['string', 'required'],
        ]);

        $tag = Tag::create($validated);

        foreach (config('language.allowed') as $lang) {
            $tag->setTranslation('name', $lang, $request->input('name'));
        }

        $tag->save();

        session()->flash('flash.banner', 'Created Tag!');
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('tags.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag): Response
    {
        return Inertia::render('mod/tags/show', [
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag): Response
    {
        return Inertia::render('mod/tags/edit', [
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['string', 'required'],
        ]);

        $tag->update($validated);

        foreach (config('language.allowed') as $lang) {
            $tag->setTranslation('name', $lang, $request->input('name'));
        }

        $tag->save();

        session()->flash('flash.banner', 'Updated Tag!');
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        session()->flash('flash.banner', 'Deleted Tag!');
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('tags.index');
    }
}

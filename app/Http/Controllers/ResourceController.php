<?php

namespace App\Http\Controllers;

use AliBayat\LaravelCategorizable\Category;
use App\Http\Requests\StoreResourceRatingRequest;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\Resource;
use App\Notifications\Resource\LikeNotification;
use App\Support\ResourcePresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;

class ResourceController extends Controller
{
    public function index(?string $name = null): Response
    {
        $selectedCategory = $name ? Category::findByName($name) : null;

        $resourcesQuery = Resource::query()
            ->with(['user', 'categories', 'updates', 'reviews'])
            ->withCount('likers')
            ->orderByDesc('created_at');

        if ($selectedCategory) {
            $categoryIds = [$selectedCategory->id];

            if ($selectedCategory->children()->count() > 0) {
                $categoryIds = array_merge(
                    $categoryIds,
                    $selectedCategory->children()->pluck('id')->all()
                );
            }

            $resourcesQuery->whereHas(
                'categories',
                fn ($query) => $query->whereIn('categories.id', $categoryIds)
            );
        }

        $categories = Category::query()
            ->whereNull('parent_id')
            ->with('children')
            ->get()
            ->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'url' => route('resource.category', $category->slug),
                'active' => $selectedCategory?->slug === $category->slug,
                'children' => $category->children->map(fn (Category $child): array => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'slug' => $child->slug,
                    'url' => route('resource.category', $child->slug),
                    'active' => $selectedCategory?->slug === $child->slug,
                ])->values()->all(),
            ])
            ->values()
            ->all();

        return Inertia::render('resources/index', [
            'resources' => $resourcesQuery
                ->paginate(15)
                ->withQueryString()
                ->through(fn (Resource $resource): array => ResourcePresenter::card($resource)),
            'categories' => $categories,
            'selectedCategory' => $selectedCategory
                ? [
                    'name' => $selectedCategory->name,
                    'slug' => $selectedCategory->slug,
                ]
                : null,
            'canCreate' => (bool) request()->user(),
            'copy' => [
                'title' => __('Resources'),
                'categories' => __('Categories'),
                'allCategories' => __('All categories'),
                'wantToAdd' => __('Want to add a resource?'),
                'create' => __('Create'),
                'rating' => __('Rating'),
                'likes' => __('Likes'),
                'downloads' => __('Downloads'),
                'updated' => __('Updated'),
                'nothingFound' => __('Nothing found.'),
            ],
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Resource::class);

        return Inertia::render('resources/create', [
            'categories' => $this->categoryOptions(),
            'copy' => [
                'title' => __('Create Resource'),
                'resources' => __('Resources'),
                'name' => __('Name'),
                'brief' => __('Brief'),
                'briefPlaceholder' => 'A brief one-line description for My Resource Pack',
                'category' => __('Category'),
                'selectCategory' => __('Select a category'),
                'description' => __('Description'),
                'submit' => __('Create'),
            ],
        ]);
    }

    public function store(StoreResourceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $resource = Resource::create([
            'name' => $validated['name'],
            'brief' => $validated['brief'],
            'description' => $validated['description'],
            'user_id' => $request->user()->id,
        ]);

        $category = Category::find($validated['category']);
        if ($category) {
            $resource->syncCategories($category);
        }

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __(':item created successfully.', ['item' => __('Resource')]));

        return redirect()->route('resource.index');
    }

    public function show(string $uuid): Response
    {
        $resource = Resource::query()
            ->with(['user', 'categories', 'updates.game_version', 'reviews.author'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        views($resource)
            ->cooldown(60)
            ->record();

        return Inertia::render('resources/show', [
            'resource' => ResourcePresenter::show($resource, request()->user()),
            'copy' => [
                'resources' => __('Resources'),
                'leaveRating' => __('Leave a rating'),
                'download' => __('Download'),
                'postUpdate' => __('Post an update'),
                'edit' => __('Edit'),
                'delete' => __('Delete'),
                'author' => __('Author'),
                'rating' => __('Rating'),
                'downloads' => __('Downloads'),
                'views' => __('Views'),
                'created' => __('Created'),
                'updated' => __('Updated'),
                'updates' => __('Updates'),
                'latestReviews' => __('Latest reviews'),
                'nothingFound' => __('Nothing found.'),
                'like' => __('Like'),
                'unlike' => __('Unlike'),
            ],
        ]);
    }

    public function edit(string $uuid): Response
    {
        $resource = $this->findResource($uuid);
        $this->authorize('update', $resource);

        return Inertia::render('resources/edit', [
            'resource' => ResourcePresenter::form($resource),
            'categories' => $this->categoryOptions(),
            'copy' => [
                'title' => __('Edit Resource'),
                'resources' => __('Resources'),
                'name' => __('Name'),
                'brief' => __('Brief'),
                'briefPlaceholder' => 'A brief one-line description for My Resource Pack',
                'category' => __('Category'),
                'selectCategory' => __('Select a category'),
                'description' => __('Description'),
                'submit' => __('Update'),
                'cancel' => __('Cancel'),
            ],
        ]);
    }

    public function update(UpdateResourceRequest $request, string $uuid): RedirectResponse
    {
        $resource = $this->findResource($uuid);
        $this->authorize('update', $resource);

        $validated = $request->validated();

        $resource->update([
            'name' => $validated['name'],
            'brief' => $validated['brief'],
            'description' => $validated['description'],
        ]);

        $category = Category::find($validated['category']);
        if ($category) {
            $resource->syncCategories($category);
        }

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __(':item updated successfully.', ['item' => __('Resource')]));

        return redirect()->route('resource.uuid', $resource->uuid);
    }

    public function delete(string $uuid): Response
    {
        $resource = $this->findResource($uuid);
        $this->authorize('delete', $resource);

        return Inertia::render('resources/delete', [
            'resource' => [
                'uuid' => $resource->uuid,
                'name' => $resource->name,
                'brief' => $resource->brief,
            ],
            'copy' => [
                'resources' => __('Resources'),
                'title' => __('Confirm Deletion'),
                'confirm' => __('Are you sure you want to delete this :item?', ['item' => strtolower(__('Resource'))]),
                'warning' => __('Warning'),
                'warningBody' => __('This action cannot be undone. All updates, reviews, and associated data will be permanently deleted.'),
                'cancel' => __('Cancel'),
                'submit' => __('Yes, Delete Resource'),
            ],
        ]);
    }

    public function destroy(string $uuid): RedirectResponse
    {
        $resource = $this->findResource($uuid);
        $this->authorize('delete', $resource);

        $resource->delete();

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __(':item deleted successfully.', ['item' => __('Resource')]));

        return redirect()->route('resource.index');
    }

    public function rate(string $uuid): Response
    {
        $resource = $this->findResource($uuid);
        $this->authorize('rate', $resource);

        return Inertia::render('resources/rate', [
            'resource' => [
                'uuid' => $resource->uuid,
                'name' => $resource->name,
            ],
            'copy' => [
                'resources' => __('Resources'),
                'title' => __('Rate this resource'),
                'clickToRate' => __('Click to rate this resource'),
                'reviewLabel' => __('Your review of this resource'),
                'reviewPlaceholder' => __('Share your thoughts about this resource...'),
                'minMax' => __('Min characters').': 10 · '.__('Max characters').': 255',
                'cancel' => __('Cancel'),
                'submit' => __('Submit Review'),
                'labels' => [
                    1 => __('Terrible'),
                    2 => __('Bad'),
                    3 => __('Okay'),
                    4 => __('Good'),
                    5 => __('Amazing'),
                ],
            ],
        ]);
    }

    public function storeRating(StoreResourceRatingRequest $request, string $uuid): RedirectResponse
    {
        $resource = $this->findResource($uuid);
        $this->authorize('rate', $resource);

        $validated = $request->validated();

        $resource->review($validated['body'], $request->user(), $validated['rating']);

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __('Thank you for your review!'));

        return redirect()->route('resource.uuid', $resource->uuid);
    }

    public function like(Request $request, string $uuid): RedirectResponse
    {
        $resource = $this->findResource($uuid);
        $this->authorize('like', $resource);

        $user = $request->user();
        $user->toggleLike($resource);

        if ($resource->isLikedBy($user)) {
            Notification::send($resource->user, new LikeNotification($resource, $user));
        }

        return back();
    }

    protected function findResource(string $uuid): Resource
    {
        return Resource::query()
            ->with('categories')
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    /**
     * @return array<int, array{id: int, name: string}>
     */
    protected function categoryOptions(): array
    {
        return Category::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->name,
            ])
            ->values()
            ->all();
    }
}

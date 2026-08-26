<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberIndexRequest;
use App\Models\User;
use App\Support\MemberPresenter;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MemberIndexRequest $request): Response
    {
        $filters = $request->filters();

        $members = User::verified()
            ->with(['gamesave', 'gamejolt'])
            ->when($filters['search'] !== '', function (Builder $query) use ($filters): void {
                $term = '%'.addcslashes($filters['search'], '%_\\').'%';

                $query->where(function (Builder $searchQuery) use ($term): void {
                    $searchQuery
                        ->where('username', 'like', $term)
                        ->orWhere('location', 'like', $term);
                });
            })
            ->when($filters['gamejolt'], fn (Builder $query): Builder => $query->whereHas('gamejolt'))
            ->when($filters['gamesave'], fn (Builder $query): Builder => $query->whereHas('gamesave'))
            ->tap(fn (Builder $query): Builder => $this->applySort($query, $filters['sort']))
            ->paginate(24)
            ->withQueryString()
            ->through(fn (User $user): array => MemberPresenter::card($user));

        return Inertia::render('members/index', [
            'members' => $members,
            'filters' => $filters,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $param): Response
    {
        $user = User::query()
            ->where('username', $param)
            ->orWhere('id', $param)
            ->with(['gamejolt.trophies', 'discord', 'twitch', 'gamesave'])
            ->firstOrFail();

        return Inertia::render('members/show', [
            'member' => MemberPresenter::show($user),
        ]);
    }

    private function applySort(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'joined' => $query->latest('created_at'),
            'joined_oldest' => $query->oldest('created_at'),
            'username' => $query->orderBy('username'),
            'username_desc' => $query->orderByDesc('username'),
            default => $query->latest('last_active_at'),
        };
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Rules\IPHostnameARecord;
use App\Rules\StrNotContain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Inertia\Response;

class ServerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth', 'verified'], only: ['create', 'store', 'edit', 'update', 'destroy', 'reactivate']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $serversQuery = Server::query()
            ->where('active', true)
            ->orderByDesc('official')
            ->orderByDesc('last_online_at')
            ->orderBy('ping');

        if ($user) {
            $serversQuery->where('user_id', '!=', $user->id);
        }

        return Inertia::render('servers/index', [
            'servers' => $serversQuery->get()->map(fn (Server $server): array => $this->present($server))->values(),
            'myServers' => $user
                ? Server::query()->where('user_id', $user->id)->get()->map(fn (Server $server): array => $this->present($server))->values()
                : [],
            'canCreate' => (bool) $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Server::class);

        return Inertia::render('servers/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Server::class);

        $validated = $this->validated($request);

        Server::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('server.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Server $server): Response
    {
        $this->authorize('update', $server);

        return Inertia::render('servers/edit', [
            'server' => $this->present($server),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server): RedirectResponse
    {
        $this->authorize('update', $server);

        $server->update($this->validated($request));

        return redirect()->route('server.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Server $server): RedirectResponse
    {
        $this->authorize('delete', $server);

        $server->delete();

        return back();
    }

    /**
     * Reactivate an inactive server by pinging it.
     */
    public function reactivate(Request $request, Server $server): RedirectResponse
    {
        $this->authorize('reactivate', $server);

        Artisan::call('server:ping '.$server->uuid.' true');
        $server->forceFill(['active' => true])->save();

        return back();
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', new StrNotContain('official'), 'ascii'],
            'host' => ['required', new StrNotContain('pokemon3d.net'), new IPHostnameARecord, 'lowercase'],
            'port' => ['required', 'integer', 'min:10', 'max:99999'],
            'description' => ['nullable', 'string'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function present(Server $server): array
    {
        return [
            'id' => $server->id,
            'uuid' => $server->uuid,
            'name' => $server->name,
            'host' => $server->host,
            'port' => $server->port,
            'description' => $server->description,
            'active' => (bool) $server->active,
            'official' => (bool) $server->official,
            'ping' => $server->ping,
            'last_online_at' => $server->last_online_at?->diffForHumans(),
            'user_id' => $server->user_id,
        ];
    }
}

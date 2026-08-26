<?php

namespace App\Actions\Server;

use App\Models\Server;
use App\Services\ServerPinger;

class PingServer
{
    public function __construct(private ServerPinger $pinger) {}

    public function execute(Server $server, bool $reactivate = false): ?int
    {
        $ping = $this->pinger->ping($server->host, $server->port);

        $server->ping = $ping;
        $server->last_check_at = now();

        if ($ping !== null) {
            $server->last_online_at = now();
            $server->active = true;
        }

        $lastSeenAt = $server->last_online_at ?? $server->created_at;

        if (
            ! $reactivate
            && $ping === null
            && ! $server->official
            && $lastSeenAt->lt(now()->subHours(24))
        ) {
            $server->active = false;
        }

        $server->save();

        return $ping;
    }
}

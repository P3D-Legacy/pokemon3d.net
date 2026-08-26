<?php

namespace App\Console\Commands;

use App\Actions\Server\PingServer as PingServerAction;
use App\Models\Server;
use Illuminate\Console\Command;

class PingServer extends Command
{
    protected $signature = 'server:ping {uuid?} {--reactivate : Skip auto-deactivation and keep the server listable}';

    protected $description = 'Ping a server, or all servers, and store the result.';

    protected $aliases = ['server:pingall'];

    public function handle(PingServerAction $pingServer): int
    {
        $uuid = $this->argument('uuid');
        $reactivate = (bool) $this->option('reactivate');

        if (is_string($uuid) && $uuid !== '') {
            return $this->pingOne($pingServer, $uuid, $reactivate);
        }

        return $this->pingAll($pingServer, $reactivate);
    }

    private function pingOne(PingServerAction $pingServer, string $uuid, bool $reactivate): int
    {
        $server = Server::query()->where('uuid', $uuid)->first();

        if (! $server) {
            $this->error('Server not found.');

            return self::FAILURE;
        }

        $ping = $pingServer->execute($server, $reactivate);
        $this->info($this->formatResult($server, $ping));

        return self::SUCCESS;
    }

    private function pingAll(PingServerAction $pingServer, bool $reactivate): int
    {
        $checked = 0;
        $reachable = 0;

        Server::query()
            ->orderBy('id')
            ->eachById(function (Server $server) use ($pingServer, $reactivate, &$checked, &$reachable): void {
                $ping = $pingServer->execute($server, $reactivate);
                $checked++;

                if ($ping !== null) {
                    $reachable++;
                }
            });

        $this->info("Pinged {$checked} server(s). {$reachable} reachable.");

        return self::SUCCESS;
    }

    private function formatResult(Server $server, ?int $ping): string
    {
        $latency = $ping === null ? 'unreachable' : "{$ping}ms";

        return "Name: {$server->name} - Ping: {$latency}";
    }
}

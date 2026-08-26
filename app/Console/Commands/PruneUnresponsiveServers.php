<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class PruneUnresponsiveServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:prune-unresponsive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft-delete unofficial servers that have been unresponsive for 7 days.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoff = now()->subDays(7);
        $deleted = 0;

        Server::query()
            ->where('official', false)
            ->whereRaw('COALESCE(last_online_at, created_at) < ?', [$cutoff])
            ->orderBy('id')
            ->chunkById(100, function ($servers) use (&$deleted): void {
                foreach ($servers as $server) {
                    $server->delete();
                    $deleted++;
                }
            });

        $this->info("Soft-deleted {$deleted} unresponsive server(s).");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PingAllServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:pingall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping all servers to update their status.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $servers = Server::all();
        foreach ($servers as $server) {
            Artisan::call('server:ping '.$server->uuid);
        }
        $this->info('All servers have been pinged.');

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class PingServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:ping {uuid} {reactivate=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping selected server and save response to model.';

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
        $server_uuid = $this->argument('uuid');
        $reactivate = $this->argument('reactivate');

        $server = Server::find($server_uuid);
        if (! $server) {
            $this->error('Server not found.');

            return;
        }

        $starttime = microtime(true);
        // supress error messages with @
        $connection = @fsockopen(
            $server->host,
            $server->port,
            $errno,
            $errstr,
            2
        );
        $stoptime = microtime(true);
        $ping = 0;

        if (! $connection) {
            $ping = null; // Site is down
        } else {
            fclose($connection);
            $time = ($stoptime - $starttime) * 1000;
            $ping = floor($time);
        }
        $server->ping = $ping;
        $server->last_check_at = now();
        if ($ping) {
            $server->last_online_at = now();
            $server->active = true;
        }
        if (
            ! $reactivate &&
            ! $ping &&
            ! $server->official &&
            $server->last_online_at < now()->subHours(24)
        ) {
            $server->active = false;
        }
        $server->save();
        $this->info('Name: '.$server->name.' - Ping: '.$ping.'ms');

        return $ping;
    }
}

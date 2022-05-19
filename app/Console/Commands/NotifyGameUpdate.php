<?php

namespace App\Console\Commands;

use App\Models\GameVersion;
use App\Models\User;
use App\Notifications\NewGameUpdateNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Notification;
use Origami\Consent\Consent;

class NotifyGameUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:gameupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about a new game update';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $latest = GameVersion::latest();
        $rls_date = $latest->release_date;
        $yesterday = Carbon::yesterday();
        $users = Consent::all()->where('name', 'email.newsletter')->where('given', '1')->where('model_type', 'App\\Models\\User')->pluck('model_id')->toArray();
        $users = User::whereIn('id', $users)->get();
        if ($rls_date->isSameDay($yesterday)) {
            $this->info("New update found: {$latest->version}");
            Notification::send($users, new NewGameUpdateNotification($latest));
        } else {
            $this->info('No new update found to notify about.');
        }
        return 0;
    }
}

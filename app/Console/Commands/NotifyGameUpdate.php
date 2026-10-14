<?php

namespace App\Console\Commands;

use App\Models\GameVersion;
use App\Models\User;
use App\Notifications\NewGameUpdateNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
     */
    public function handle(): int
    {
        $latest = GameVersion::latest();

        if ($latest === null || ! $latest->release_date?->isSameDay(Carbon::yesterday())) {
            $this->info('No new update found to notify about.');

            return Command::SUCCESS;
        }

        $this->info("New update found: {$latest->version}");

        User::query()->orderBy('id')->lazy()->each(function (User $user) use ($latest): void {
            if (! $user->getConsent('email.newsletter')) {
                return;
            }

            $user->notify(new NewGameUpdateNotification($latest));
        });

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Actions\Save\NotifyGameSaveFixRequestChanged;
use App\Models\GameSaveFixRequest;
use Illuminate\Console\Command;

class NotifyStaleGameSaveFixRequests extends Command
{
    protected $signature = 'game-save-fix:notify-stale';

    protected $description = 'Send Discord reminders for save fix requests idle for 7 days';

    public function handle(NotifyGameSaveFixRequestChanged $notifier): int
    {
        $days = (int) config('game-save.stale_after_days', 7);

        $count = 0;

        GameSaveFixRequest::query()
            ->stale($days)
            ->with(['user', 'assignee'])
            ->each(function (GameSaveFixRequest $request) use ($notifier, &$count): void {
                $notifier->stale($request);
                $count++;
            });

        $this->info("Notified Discord about {$count} stale save fix request(s).");

        return self::SUCCESS;
    }
}

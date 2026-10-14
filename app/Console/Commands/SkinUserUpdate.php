<?php

namespace App\Console\Commands;

use App\Models\GamejoltAccount;
use App\Models\Like;
use App\Models\Skin;
use Illuminate\Console\Command;

class SkinUserUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p3d:skinuserupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update skins with user instead of old gj owner id.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $skins = Skin::query()->whereNull('user_id')->get();

        $accounts = GamejoltAccount::query()
            ->whereIn('id', $skins->pluck('owner_id')->unique()->filter())
            ->get()
            ->keyBy(fn (GamejoltAccount $account): string => (string) $account->id);

        foreach ($skins as $skin) {
            $account = $accounts->get((string) $skin->owner_id);

            if ($account === null || $account->user_id === null) {
                continue;
            }

            $skin->update(['user_id' => $account->user_id]);
            $this->info("Skin #{$skin->id} updated.");

            $likeIds = Like::query()
                ->where('user_id', $skin->owner_id)
                ->pluck('id');

            if ($likeIds->isEmpty()) {
                continue;
            }

            Like::query()
                ->whereIn('id', $likeIds)
                ->update(['user_id' => $account->user_id]);

            foreach ($likeIds as $likeId) {
                $this->info("Like #{$likeId} updated.");
            }
        }

        $this->info('All done!');

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Skin;
use App\Models\GamejoltAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $skins = Skin::where('user_id', null)->get();
        foreach ($skins as $skin) {
            $gja = GamejoltAccount::where('id', $skin->owner_id)->first();
            if ($gja) {
                $skin->update(['user_id' => $gja->user_id]);
                $this->info('Skin #' . $skin->id . ' updated.');
                $rows = DB::select(
                    'SELECT * FROM likes WHERE user_id = ' . $skin->owner_id
                );
                foreach ($rows as $row) {
                    DB::update(
                        'UPDATE likes SET user_id = ' .
                            $gja->user_id .
                            ' WHERE user_id = ' .
                            $skin->owner_id
                    );
                    $this->info('Like #' . $row->id . ' updated.');
                }
            }
        }
    }
}

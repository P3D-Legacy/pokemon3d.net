<?php

namespace Database\Seeders;

use App\Models\BanReason;
use Illuminate\Database\Seeder;

class BanReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $reasons = [
            'Abusing in-game glitches',
            'General hacking or cheating',
            'Hacking in Rankings and/or points',
            'Hacking in Pokemon data',
            'Using strong language in-game',
            'Cheating in PvP',
            'Hacking in emblem(s)',
            'Certain actions on the GTS',
            'Using a skin that isn\'t conform to the rules',
        ];

        foreach ($reasons as $reason) {
            BanReason::firstOrCreate([
                'name' => $reason,
                'user_id' => 1,
            ]);
        }
    }
}

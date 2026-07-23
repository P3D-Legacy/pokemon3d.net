<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $achievementNames = [
        'AssociatedFacebook',
        'AssociatedTwitter',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $achievementIds = DB::table('achievement_details')
            ->whereIn('name', $this->achievementNames)
            ->pluck('id');

        if ($achievementIds->isEmpty()) {
            return;
        }

        DB::table('achievement_progress')
            ->whereIn('achievement_id', $achievementIds)
            ->delete();

        DB::table('achievement_details')
            ->whereIn('id', $achievementIds)
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $now = now();

        DB::table('achievement_details')->insert([
            [
                'name' => 'AssociatedTwitter',
                'description' => 'User associated their account with Twitter.',
                'points' => 1,
                'secret' => 0,
                'class_name' => 'App\\Achievements\\User\\AssociatedTwitter',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'AssociatedFacebook',
                'description' => 'User associated their account with Facebook.',
                'points' => 1,
                'secret' => 0,
                'class_name' => 'App\\Achievements\\User\\AssociatedFacebook',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
};

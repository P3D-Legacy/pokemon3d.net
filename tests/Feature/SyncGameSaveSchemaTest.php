<?php

use App\Models\GameSave;
use Illuminate\Support\Facades\Schema;

it('lists game_saves columns using the same Schema call as SyncGameSaveForUser', function () {
    $model = new GameSave;
    $columns = Schema::getColumnListing($model->getTable());

    expect($columns)->not->toBeEmpty()
        ->and($columns)->toContain('user_id');
});

<?php

use App\Models\GameSave;

it('lists game_saves columns using the same schema builder call as SyncGameSaveForUser', function () {
    $model = new GameSave;
    $columns = $model->getConnection()
        ->getSchemaBuilder()
        ->getColumnListing($model->getTable());

    expect($columns)->not->toBeEmpty()
        ->and($columns)->toContain('user_id');
});

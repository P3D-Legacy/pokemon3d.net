<?php

namespace App\Filament\Resources\GameVersionResource\Pages;

use App\Filament\Resources\GameVersionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameVersion extends EditRecord
{
    protected static string $resource = GameVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\GamejoltAccountBanResource\Pages;

use App\Filament\Resources\GamejoltAccountBanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGamejoltAccountBan extends EditRecord
{
    protected static string $resource = GamejoltAccountBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

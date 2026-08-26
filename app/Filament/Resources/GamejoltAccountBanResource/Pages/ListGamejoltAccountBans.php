<?php

namespace App\Filament\Resources\GamejoltAccountBanResource\Pages;

use App\Filament\Resources\GamejoltAccountBanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGamejoltAccountBans extends ListRecords
{
    protected static string $resource = GamejoltAccountBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

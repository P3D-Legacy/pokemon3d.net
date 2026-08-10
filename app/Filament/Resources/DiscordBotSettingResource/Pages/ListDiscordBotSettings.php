<?php

namespace App\Filament\Resources\DiscordBotSettingResource\Pages;

use App\Filament\Resources\DiscordBotSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscordBotSettings extends ListRecords
{
    protected static string $resource = DiscordBotSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

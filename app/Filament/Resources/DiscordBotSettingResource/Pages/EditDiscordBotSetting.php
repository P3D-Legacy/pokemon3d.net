<?php

namespace App\Filament\Resources\DiscordBotSettingResource\Pages;

use App\Filament\Resources\DiscordBotSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscordBotSetting extends EditRecord
{
    protected static string $resource = DiscordBotSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

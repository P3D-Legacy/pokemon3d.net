<?php

namespace App\Filament\Resources\ResourcePackUpdateResource\Pages;

use App\Filament\Resources\ResourcePackUpdateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResourcePackUpdate extends EditRecord
{
    protected static string $resource = ResourcePackUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

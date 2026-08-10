<?php

namespace App\Filament\Resources\ResourcePackResource\Pages;

use App\Filament\Resources\ResourcePackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResourcePack extends EditRecord
{
    protected static string $resource = ResourcePackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

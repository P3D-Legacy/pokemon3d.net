<?php

namespace App\Filament\Resources\ResourcePackResource\Pages;

use App\Filament\Resources\ResourcePackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResourcePacks extends ListRecords
{
    protected static string $resource = ResourcePackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

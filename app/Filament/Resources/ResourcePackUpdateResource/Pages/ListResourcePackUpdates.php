<?php

namespace App\Filament\Resources\ResourcePackUpdateResource\Pages;

use App\Filament\Resources\ResourcePackUpdateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResourcePackUpdates extends ListRecords
{
    protected static string $resource = ResourcePackUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\SkinResource\Pages;

use App\Filament\Resources\SkinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkins extends ListRecords
{
    protected static string $resource = SkinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

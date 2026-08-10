<?php

namespace App\Filament\Resources\BanReasonResource\Pages;

use App\Filament\Resources\BanReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanReasons extends ListRecords
{
    protected static string $resource = BanReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

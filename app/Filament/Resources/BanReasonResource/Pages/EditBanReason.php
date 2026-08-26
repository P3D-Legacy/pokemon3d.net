<?php

namespace App\Filament\Resources\BanReasonResource\Pages;

use App\Filament\Resources\BanReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBanReason extends EditRecord
{
    protected static string $resource = BanReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\SkinResource\Pages;

use App\Filament\Resources\SkinResource;
use App\Support\SkinStorage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditSkin extends EditRecord
{
    protected static string $resource = SkinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $image = $this->data['image'] ?? null;

        if ($image instanceof TemporaryUploadedFile) {
            SkinStorage::storeLibrary($image, $this->record->uuid);
        }
    }
}

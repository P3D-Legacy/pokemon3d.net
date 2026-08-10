<?php

namespace App\Filament\Resources\SkinResource\Pages;

use App\Filament\Resources\SkinResource;
use App\Support\SkinStorage;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateSkin extends CreateRecord
{
    protected static string $resource = SkinResource::class;

    protected function afterCreate(): void
    {
        $this->storeSkinImage();
    }

    protected function storeSkinImage(): void
    {
        $image = $this->data['image'] ?? null;

        if ($image instanceof TemporaryUploadedFile) {
            SkinStorage::storeLibrary($image, $this->record->uuid);
        }
    }
}

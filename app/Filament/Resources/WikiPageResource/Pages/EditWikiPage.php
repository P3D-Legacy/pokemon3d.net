<?php

namespace App\Filament\Resources\WikiPageResource\Pages;

use App\Filament\Resources\WikiPageResource;
use App\Support\WikiSlug;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWikiPage extends EditRecord
{
    protected static string $resource = WikiPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['title'])) {
            $data['slug'] = WikiSlug::unique($data['title'], $this->record);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->recordRevision(auth()->user(), __('Updated page'));
    }
}

<?php

namespace App\Filament\Resources\WikiPageResource\Pages;

use App\Filament\Resources\WikiPageResource;
use App\Support\WikiSlug;
use Filament\Resources\Pages\CreateRecord;

class CreateWikiPage extends CreateRecord
{
    protected static string $resource = WikiPageResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = WikiSlug::unique($data['title']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->recordRevision(auth()->user(), __('Created page'));
    }
}

<?php

namespace App\Filament\Resources\GameSaveFixRequestResource\Pages;

use App\Actions\Save\ManageGameSaveFixRequest;
use App\Enums\GameSaveFixRequestStatus;
use App\Filament\Resources\GameSaveFixRequestResource;
use App\Jobs\SyncGameSaveForUser;
use App\Models\GameSaveFixRequest;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewGameSaveFixRequest extends ViewRecord
{
    protected static string $resource = GameSaveFixRequestResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        /** @var GameSaveFixRequest $fixRequest */
        $fixRequest = $this->getRecord();
        $fixRequest->loadMissing('user');

        if ($fixRequest->user) {
            SyncGameSaveForUser::dispatch($fixRequest->user);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('claim')
                ->label('Claim')
                ->icon('heroicon-o-hand-raised')
                ->visible(fn (): bool => $this->canUpdate()
                    && $this->getRecord()->status === GameSaveFixRequestStatus::Open)
                ->requiresConfirmation()
                ->action(function (ManageGameSaveFixRequest $manager): void {
                    $manager->claim($this->getRecord(), auth()->user());
                    $this->record->refresh()->load(['user', 'assignee']);
                    Notification::make()->title('Request claimed')->success()->send();
                }),
            Actions\Action::make('resolve')
                ->label('Resolve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->canUpdate()
                    && $this->getRecord()->status === GameSaveFixRequestStatus::Claimed)
                ->requiresConfirmation()
                ->action(function (ManageGameSaveFixRequest $manager): void {
                    $manager->resolve($this->getRecord());
                    $this->record->refresh()->load(['user', 'assignee']);
                    Notification::make()->title('Request resolved')->success()->send();
                }),
        ];
    }

    private function canUpdate(): bool
    {
        return auth()->user()?->can('game_save_fix.update') ?? false;
    }
}

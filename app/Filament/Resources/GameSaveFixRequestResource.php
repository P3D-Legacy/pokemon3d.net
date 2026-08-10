<?php

namespace App\Filament\Resources;

use App\Enums\GameSaveFixRequestStatus;
use App\Filament\Resources\GameSaveFixRequestResource\Pages;
use App\Models\GameSaveFixRequest;
use App\Support\GameSavePresenter;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class GameSaveFixRequestResource extends Resource
{
    protected static ?string $model = GameSaveFixRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Moderation';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'uuid';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('game_save_fix.show') ?? false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->can('game_save_fix.show') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Request')
                    ->schema([
                        TextEntry::make('uuid')->label('ID'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (GameSaveFixRequestStatus $state): string => $state->label()),
                        TextEntry::make('user.username')->label('Requester'),
                        TextEntry::make('assignee.username')->label('Assignee')->placeholder('Unassigned'),
                        TextEntry::make('description')->columnSpanFull(),
                        TextEntry::make('consent_text')->label('Consent')->columnSpanFull(),
                        TextEntry::make('consent_accepted_at')->dateTime(),
                        TextEntry::make('resolved_at')->dateTime()->placeholder('Not resolved'),
                        TextEntry::make('created_at')->dateTime(),
                        TextEntry::make('updated_at')->dateTime(),
                    ])
                    ->columns(2),
                Section::make('Synced save summary')
                    ->schema([
                        TextEntry::make('save_summary')
                            ->label('')
                            ->state(fn (GameSaveFixRequest $record): string => static::saveSummary($record))
                            ->markdown()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.username')
                    ->label('Requester')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (GameSaveFixRequestStatus $state): string => $state->label())
                    ->sortable(),
                TextColumn::make('assignee.username')
                    ->label('Assignee')
                    ->placeholder('Unassigned')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(GameSaveFixRequestStatus::cases())
                        ->mapWithKeys(fn (GameSaveFixRequestStatus $status): array => [
                            $status->value => $status->label(),
                        ])
                        ->all()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGameSaveFixRequests::route('/'),
            'view' => Pages\ViewGameSaveFixRequest::route('/{record}'),
        ];
    }

    private static function saveSummary(GameSaveFixRequest $record): string
    {
        $record->loadMissing('user');

        if (! $record->user) {
            return 'No requester is linked to this request.';
        }

        $payload = GameSavePresenter::forOwner($record->user);

        if (! ($payload['available'] ?? false)) {
            return $payload['message'] ?? 'No game save is available yet.';
        }

        $details = $payload['details'] ?? [];
        $lines = [
            '- **Last synced:** '.($payload['last_synced'] ?? 'Unknown'),
            '- **Caught:** '.($payload['caught_count'] ?? 0),
            '- **Seen:** '.($payload['seen_count'] ?? 0),
            '- **Party Pokémon:** '.count($payload['party'] ?? []),
            '- **Box Pokémon:** '.count($payload['box'] ?? []),
            '- **Bag items:** '.count($payload['items'] ?? []),
        ];

        if (! empty($details['Name'])) {
            array_unshift($lines, '- **Player:** '.$details['Name']);
        }

        if (isset($details['Money'])) {
            $lines[] = '- **Money:** '.$details['Money'];
        }

        return implode("\n", $lines);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GamejoltAccountBanResource\Pages;
use App\Models\GamejoltAccountBan;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GamejoltAccountBanResource extends Resource
{
    protected static ?string $model = GamejoltAccountBan::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';

    protected static ?string $navigationGroup = 'Moderation';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('gamejolt_account_ban.show') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('gamejolt_account_ban.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('gamejolt_account_ban.create') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('gamejolt_account_ban.destroy') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('gamejoltaccount_id')
                    ->label('Gamejolt Account')
                    ->relationship('gamejoltaccount', 'username')
                    ->searchable()
                    ->required(),
                Select::make('banned_by_id')
                    ->label('Banned by')
                    ->relationship('banned_by', 'name')
                    ->searchable()
                    ->required(),
                Select::make('reason_id')
                    ->label('Reason')
                    ->relationship('reason', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gamejoltaccount.username')
                    ->label('Gamejolt Account')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('banned_by.name')
                    ->label('Banned by')
                    ->sortable(),
                TextColumn::make('reason.name')
                    ->label('Reason')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGamejoltAccountBans::route('/'),
            'create' => Pages\CreateGamejoltAccountBan::route('/create'),
            'edit' => Pages\EditGamejoltAccountBan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

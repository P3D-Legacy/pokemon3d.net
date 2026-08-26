<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourcePackUpdateResource\Pages;
use App\Models\ResourceUpdate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResourcePackUpdateResource extends Resource
{
    protected static ?string $model = ResourceUpdate::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Resource Pack Updates';

    protected static ?string $modelLabel = 'Resource Pack Update';

    protected static ?string $pluralModelLabel = 'Resource Pack Updates';

    protected static ?string $slug = 'resource-pack-updates';

    protected static ?string $navigationGroup = 'Game';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('resource_id')
                    ->label('Resource Pack')
                    ->relationship('resource', 'name')
                    ->searchable()
                    ->required(),
                Select::make('game_version_id')
                    ->label('Game Version')
                    ->relationship('game_version', 'title')
                    ->searchable()
                    ->required(),
                TextInput::make('external_download_url')
                    ->label('External Download URL')
                    ->url()
                    ->nullable()
                    ->rule('starts_with:https')
                    ->maxLength(2048),
                TextInput::make('downloads')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('resource.name')
                    ->label('Resource Pack')
                    ->sortable(),
                TextColumn::make('game_version.title')
                    ->label('Game Version')
                    ->sortable(),
                TextColumn::make('downloads')
                    ->sortable(),
                TextColumn::make('external_download_url')
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResourcePackUpdates::route('/'),
            'create' => Pages\CreateResourcePackUpdate::route('/create'),
            'edit' => Pages\EditResourcePackUpdate::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameVersionResource\Pages;
use App\Models\GameVersion;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GameVersionResource extends Resource
{
    protected static ?string $model = GameVersion::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Game';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('version')
                    ->required()
                    ->maxLength(255),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('release_date')
                    ->required(),
                TextInput::make('page_url')
                    ->url()
                    ->required()
                    ->maxLength(2048),
                TextInput::make('download_url')
                    ->url()
                    ->required()
                    ->maxLength(2048),
                Select::make('post_id')
                    ->label('Post')
                    ->relationship('post', 'title')
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('release_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('page_url')
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('download_url')
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('post.title')
                    ->sortable(),
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
            'index' => Pages\ListGameVersions::route('/'),
            'create' => Pages\CreateGameVersion::route('/create'),
            'edit' => Pages\EditGameVersion::route('/{record}/edit'),
        ];
    }
}

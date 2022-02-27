<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameVersionResource\Pages;
use App\Filament\Resources\GameVersionResource\RelationManagers;
use App\Models\GameVersion;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class GameVersionResource extends Resource
{
    protected static ?string $model = GameVersion::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Game';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('version')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('release_date')
                    ->required(),
                Forms\Components\TextInput::make('page_url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('download_url')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('version'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('release_date')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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

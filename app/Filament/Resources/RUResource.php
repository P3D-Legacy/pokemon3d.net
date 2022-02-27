<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RUResource\Pages;
use App\Filament\Resources\RUResource\RelationManagers;
use App\Models\ResourceUpdate as ModelsResourceUpdate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class RUResource extends Resource
{
    protected static ?string $model = ModelsResourceUpdate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Resource Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->required()
                ->maxLength(65535),
            Forms\Components\TextInput::make('resource_id')->required(),
            Forms\Components\TextInput::make('game_version_id')->required(),
            Forms\Components\TextInput::make('downloads')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('resource_id'),
                Tables\Columns\TextColumn::make('game_version_id'),
                Tables\Columns\TextColumn::make('downloads'),
                Tables\Columns\TextColumn::make('deleted_at')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
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
            'index' => Pages\ListRUS::route('/'),
            //'create' => Pages\CreateRU::route('/create'),
            'edit' => Pages\EditRU::route('/{record}/edit'),
        ];
    }
}

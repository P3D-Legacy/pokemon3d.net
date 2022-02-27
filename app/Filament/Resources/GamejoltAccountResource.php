<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GamejoltAccountResource\Pages;
use App\Filament\Resources\GamejoltAccountResource\RelationManagers;
use App\Models\GamejoltAccount;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class GamejoltAccountResource extends Resource
{
    protected static ?string $model = GamejoltAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Game Jolt';

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('username'),
                Tables\Columns\TextColumn::make('user.username'),
                Tables\Columns\TextColumn::make('verified_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->bulkActions([
                // ...
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
            'index' => Pages\ListGamejoltAccounts::route('/'),
            //'create' => Pages\CreateGamejoltAccount::route('/create'),
            //'edit' => Pages\EditGamejoltAccount::route('/{record}/edit'),
        ];
    }
}

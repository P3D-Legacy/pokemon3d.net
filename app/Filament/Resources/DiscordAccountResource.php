<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscordAccountResource\Pages;
use App\Filament\Resources\DiscordAccountResource\RelationManagers;
use App\Models\DiscordAccount;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DiscordAccountResource extends Resource
{
    protected static ?string $model = DiscordAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Discord';

    public static function form(Form $form): Form
    {
        return $form->schema([Forms\Components\TextInput::make('user_id')->required()]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('username'),
                Tables\Columns\TextColumn::make('discriminator'),
                //Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('verified_at')->dateTime(),
                Tables\Columns\TextColumn::make('user.username'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
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
            'index' => Pages\ListDiscordAccounts::route('/'),
            //'create' => Pages\CreateDiscordAccount::route('/create'),
            //'edit' => Pages\EditDiscordAccount::route('/{record}/edit'),
        ];
    }
}

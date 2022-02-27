<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscordRoleResource\Pages;
use App\Filament\Resources\DiscordRoleResource\RelationManagers;
use App\Models\DiscordRole;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DiscordRoleResource extends Resource
{
    protected static ?string $model = DiscordRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Discord';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id')->required(),
            Forms\Components\TextInput::make('color')->required(),
            Forms\Components\Toggle::make('hoist')->required(),
            Forms\Components\Toggle::make('managed')->required(),
            Forms\Components\Toggle::make('mentionable')->required(),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('permissions')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('position')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\BooleanColumn::make('hoist'),
                Tables\Columns\BooleanColumn::make('managed'),
                Tables\Columns\BooleanColumn::make('mentionable'),
                Tables\Columns\TextColumn::make('permissions'),
                Tables\Columns\TextColumn::make('position'),
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
            'index' => Pages\ListDiscordRoles::route('/'),
            //'create' => Pages\CreateDiscordRole::route('/create'),
            //'edit' => Pages\EditDiscordRole::route('/{record}/edit'),
        ];
    }
}

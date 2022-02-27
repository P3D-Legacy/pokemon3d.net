<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Skin;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\SkinResource\Pages;
use App\Filament\Resources\SkinResource\RelationManagers;

class SkinResource extends Resource
{
    protected static ?string $model = Skin::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('owner_id')
                ->name('Game Jolt ID')
                ->required(),
            Forms\Components\BelongsToSelect::make('user_id')
                ->relationship('user', 'username')
                ->searchable()
                ->options(User::all()->pluck('username', 'id')),
            Forms\Components\Toggle::make('public')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('owner_id')->name('Game Jolt ID'),
                Tables\Columns\TextColumn::make('user.username'),
                Tables\Columns\BooleanColumn::make('public'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
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
            'index' => Pages\ListSkins::route('/'),
            //'create' => Pages\CreateSkin::route('/create'),
            'edit' => Pages\EditSkin::route('/{record}/edit'),
        ];
    }
}

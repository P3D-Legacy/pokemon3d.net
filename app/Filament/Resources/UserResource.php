<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('username')->required()->maxLength(255),
            Forms\Components\Textarea::make('about')->maxLength(65535),
            Forms\Components\TextInput::make('gender')->required(),
            Forms\Components\TextInput::make('location')->maxLength(255),
            Forms\Components\DateTimePicker::make('birthdate'),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required()
                ->maxLength(255),
            BelongsToManyMultiSelect::make('roles')->relationship('roles', 'name')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('username'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('birthdate')->date(),
                Tables\Columns\TextColumn::make('locale'),
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
            'index' => Pages\ListUsers::route('/'),
            //'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

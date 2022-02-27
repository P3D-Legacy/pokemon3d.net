<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Server;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('host')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('port')->required(),
            Forms\Components\Textarea::make('description')->maxLength(65535),
            Forms\Components\Toggle::make('official')->required(),
            Forms\Components\Toggle::make('active')->required(),
            Forms\Components\BelongsToSelect::make('user_id')
                ->relationship('user', 'username')
                ->searchable()
                ->options(User::all()->pluck('username', 'id')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('host'),
                Tables\Columns\TextColumn::make('port'),
                Tables\Columns\BooleanColumn::make('official'),
                Tables\Columns\BooleanColumn::make('active'),
                Tables\Columns\TextColumn::make('user.username'),
                Tables\Columns\TextColumn::make('last_online_at')->dateTime(),
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
            'index' => Pages\ListServers::route('/'),
            //'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}

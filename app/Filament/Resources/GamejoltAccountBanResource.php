<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\BanReason;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\GamejoltAccountBan;
use App\Filament\Resources\GamejoltAccountBanResource\Pages;
use App\Filament\Resources\GamejoltAccountBanResource\RelationManagers;

class GamejoltAccountBanResource extends Resource
{
    protected static ?string $model = GamejoltAccountBan::class;

    protected static ?string $navigationIcon = 'heroicon-o-ban';

    protected static ?string $navigationGroup = 'Game Jolt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('banned_by_id')
                    ->relationship('banned_by', 'username')
                    ->searchable()
                    ->options(User::all()->pluck('username', 'id')),
                Forms\Components\BelongsToSelect::make('reason_id')
                    ->relationship('reason', 'name')
                    ->searchable()
                    ->options(BanReason::all()->pluck('name', 'id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gamejoltaccount.username'),
                Tables\Columns\TextColumn::make('banned_by.username'),
                Tables\Columns\TextColumn::make('reason.name'),
                Tables\Columns\TextColumn::make('expire_at')
                    ->dateTime(),
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
            'index' => Pages\ListGamejoltAccountBans::route('/'),
            //'create' => Pages\CreateGamejoltAccountBan::route('/create'),
            'edit' => Pages\EditGamejoltAccountBan::route('/{record}/edit'),
        ];
    }
}

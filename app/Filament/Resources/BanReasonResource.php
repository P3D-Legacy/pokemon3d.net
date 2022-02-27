<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BanReasonResource\Pages;
use App\Filament\Resources\BanReasonResource\RelationManagers;
use App\Models\BanReason;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BanReasonResource extends Resource
{
    protected static ?string $model = BanReason::class;

    protected static ?string $navigationIcon = 'heroicon-o-ban';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author'),
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
            'index' => Pages\ListBanReasons::route('/'),
            'create' => Pages\CreateBanReason::route('/create'),
            'edit' => Pages\EditBanReason::route('/{record}/edit'),
        ];
    }
}

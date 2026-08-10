<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscordBotSettingResource\Pages;
use App\Models\DiscordBotSetting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DiscordBotSettingResource extends Resource
{
    protected static ?string $model = DiscordBotSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('discord_bot_setting.show') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('discord_bot_setting.update') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('discord_bot_setting.update') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('category_id')
                    ->numeric()
                    ->required(),
                TextInput::make('chat_id')
                    ->numeric()
                    ->required(),
                TextInput::make('events_id')
                    ->numeric()
                    ->required(),
                Toggle::make('hide_events')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('category_id')
                    ->sortable(),
                TextColumn::make('chat_id')
                    ->sortable(),
                TextColumn::make('events_id')
                    ->sortable(),
                IconColumn::make('hide_events')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscordBotSettings::route('/'),
            'create' => Pages\CreateDiscordBotSetting::route('/create'),
            'edit' => Pages\EditDiscordBotSetting::route('/{record}/edit'),
        ];
    }
}

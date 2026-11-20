<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Community';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage.users') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('username')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(254)
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->rule(Password::defaults())
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                DatePicker::make('birthdate'),
                Select::make('gender')
                    ->options([
                        0 => 'No selection',
                        1 => 'Male',
                        2 => 'Female',
                        3 => 'Genderless',
                    ])
                    ->default(0)
                    ->required(),
                TextInput::make('location')
                    ->maxLength(255),
                Textarea::make('about')
                    ->maxLength(255)
                    ->columnSpanFull(),
                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email address copied'),
                TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
                IconColumn::make('gamejolt_exists')
                    ->label('GameJolt')
                    ->boolean(),
                TextColumn::make('email_verified_at')
                    ->label('Email verified')
                    ->dateTime()
                    ->placeholder('Unverified')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email verification')
                    ->nullable()
                    ->placeholder('All users')
                    ->trueLabel('Verified')
                    ->falseLabel('Unverified'),
                TernaryFilter::make('gamejolt')
                    ->label('GameJolt account')
                    ->placeholder('All users')
                    ->trueLabel('With GameJolt')
                    ->falseLabel('Without GameJolt')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereHas('gamejolt'),
                        false: fn (Builder $query): Builder => $query->whereDoesntHave('gamejolt'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('verificationLink')
                    ->label('Verification link')
                    ->icon('heroicon-o-link')
                    ->visible(fn (User $record): bool => ! $record->hasVerifiedEmail())
                    ->modalHeading('Email verification link')
                    ->modalDescription(fn (): string => 'This temporary signed link expires after '.(int) config('auth.verification.expire', 60).' minutes. Copy it and send it to the user if their mail host is blocking website email.')
                    ->form([
                        TextInput::make('url')
                            ->label('Link')
                            ->readOnly()
                            ->dehydrated(false)
                            ->extraInputAttributes(['onclick' => 'this.select()']),
                    ])
                    ->fillForm(fn (User $record): array => [
                        'url' => $record->emailVerificationUrl(),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Action::make('activities')
                    ->url(fn (User $record): string => UserResource::getUrl('activities', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('roles')
            ->withExists('gamejolt');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'activities' => Pages\ListUserActivities::route('/{record}/activities'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

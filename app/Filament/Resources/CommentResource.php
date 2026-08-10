<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Community';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('active')
                    ->default(true),
                Select::make('creator_id')
                    ->label('Creator')
                    ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('creator_type', User::class)),
                Select::make('creator_type')
                    ->options([
                        User::class => 'User',
                    ])
                    ->default(User::class)
                    ->required()
                    ->hidden(),
                Select::make('commentable_id')
                    ->label('Post')
                    ->options(fn (): array => Post::query()->orderBy('title')->pluck('title', 'id')->all())
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('commentable_type', Post::class)),
                Select::make('commentable_type')
                    ->options([
                        Post::class => 'Post',
                    ])
                    ->default(Post::class)
                    ->required()
                    ->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('body')
                    ->limit(50)
                    ->searchable(),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('creator.name')
                    ->label('Creator')
                    ->sortable(),
                TextColumn::make('commentable.title')
                    ->label('Post')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\SpatieTagsColumn;
use App\Filament\Resources\PostResource\Pages;
use Filament\Forms\Components\SpatieTagsInput;
use App\Filament\Resources\PostResource\RelationManagers;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\MarkdownEditor::make('body')->required(),
            Forms\Components\Toggle::make('active')->required(),
            Forms\Components\Toggle::make('sticky')->required(),
            SpatieTagsInput::make('tags'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\BooleanColumn::make('active'),
                Tables\Columns\BooleanColumn::make('sticky'),
                SpatieTagsColumn::make('tags'),
                Tables\Columns\TextColumn::make('user.username'),
                Tables\Columns\TextColumn::make('published_at')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')->dateTime(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

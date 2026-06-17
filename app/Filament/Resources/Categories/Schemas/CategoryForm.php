<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Ad')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(Category::class, 'slug', ignoreRecord: true)
                ->maxLength(255),

            Select::make('parent_id')
                ->label('Üst Kategori')
                ->relationship('parent', 'name')
                ->searchable()
                ->preload()
                ->nullable(),

            TextInput::make('icon')
                ->label('İkon (Heroicon adı)')
                ->placeholder('heroicon-o-cpu-chip')
                ->maxLength(100),

            Textarea::make('description')
                ->label('Açıklama')
                ->rows(3),

            Textarea::make('landing_intro')
                ->label('Kategori landing metni')
                ->rows(4)
                ->helperText('Kategori sayfası hero alanında gösterilir.'),

            TextInput::make('sort')
                ->label('Sıralama')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}

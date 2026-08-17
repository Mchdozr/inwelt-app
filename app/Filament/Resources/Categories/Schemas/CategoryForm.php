<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([
                Tabs\Tab::make('Genel')->schema([
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
                    TextInput::make('icon')->label('İkon (Heroicon adı)')->maxLength(100),
                    TextInput::make('sort')->label('Sıralama')->numeric()->default(0),
                    Toggle::make('is_active')->label('Aktif')->default(true),
                ]),
                Tabs\Tab::make('İçerik')->schema([
                    Textarea::make('description')->label('Açıklama')->rows(3),
                    Textarea::make('landing_intro')->label('Kategori landing metni')->rows(4),
                    RichEditor::make('seo_content')->label('SEO içerik (600+ kelime)')->columnSpanFull(),
                ]),
                Tabs\Tab::make('SEO')->schema([
                    TextInput::make('seo_title')->label('SEO Başlık')->maxLength(70),
                    Textarea::make('seo_description')->label('SEO Açıklama')->rows(3)->maxLength(160),
                    Repeater::make('faq_items')->label('Kategori SSS')->schema([
                        TextInput::make('question')->label('Soru')->required(),
                        Textarea::make('answer')->label('Cevap')->required(),
                    ])->defaultItems(0)->collapsible(),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()
                ->persistTabInQueryString('kategori-tab')
                ->tabs([
                    Tabs\Tab::make('Genel')
                        ->icon('heroicon-o-folder')
                        ->schema([
                            Section::make('Kimlik')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Ad')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                                            $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                    TextInput::make('slug')
                                        ->label('URL slug')
                                        ->required()
                                        ->unique(Category::class, 'slug', ignoreRecord: true)
                                        ->maxLength(255),

                                    Select::make('parent_id')
                                        ->label('Üst kategori')
                                        ->relationship('parent', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),

                                    TextInput::make('icon')
                                        ->label('İkon')
                                        ->helperText('Heroicon adı')
                                        ->maxLength(100),
                                ]),

                            Section::make('Durum')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('sort')
                                        ->label('Sıralama')
                                        ->numeric()
                                        ->default(0)
                                        ->helperText('Küçük = önce'),

                                    Toggle::make('is_active')
                                        ->label('Aktif')
                                        ->default(true)
                                        ->inline(false),
                                ]),
                        ]),

                    Tabs\Tab::make('İçerik')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Metinler')
                                ->schema([
                                    Textarea::make('description')
                                        ->label('Kısa açıklama')
                                        ->rows(3)
                                        ->columnSpanFull(),

                                    Textarea::make('landing_intro')
                                        ->label('Landing giriş metni')
                                        ->rows(4)
                                        ->columnSpanFull(),

                                    RichEditor::make('seo_content')
                                        ->label('SEO içerik (600+ kelime)')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            Section::make('Arama motoru')
                                ->schema([
                                    TextInput::make('seo_title')
                                        ->label('SEO başlık')
                                        ->maxLength(70)
                                        ->helperText('Önerilen: 50–70 karakter')
                                        ->columnSpanFull(),

                                    Textarea::make('seo_description')
                                        ->label('SEO açıklama')
                                        ->rows(3)
                                        ->maxLength(160)
                                        ->helperText('Önerilen: 120–160 karakter')
                                        ->columnSpanFull(),
                                ]),

                            Section::make('SSS')
                                ->schema([
                                    Repeater::make('faq_items')
                                        ->label('Kategori SSS')
                                        ->schema([
                                            TextInput::make('question')->label('Soru')->required()->columnSpanFull(),
                                            Textarea::make('answer')->label('Cevap')->required()->rows(3)->columnSpanFull(),
                                        ])
                                        ->defaultItems(0)
                                        ->collapsible()
                                        ->cloneable()
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}

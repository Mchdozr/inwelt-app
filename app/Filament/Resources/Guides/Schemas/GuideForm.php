<?php

namespace App\Filament\Resources\Guides\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()
                ->persistTabInQueryString('rehber-tab')
                ->tabs([
                    Tabs\Tab::make('Genel')
                        ->icon('heroicon-o-book-open')
                        ->schema([
                            Section::make('Kimlik')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Başlık')
                                        ->required()
                                        ->maxLength(180)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                                            $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                        ->columnSpanFull(),

                                    TextInput::make('slug')
                                        ->label('URL slug')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->columnSpanFull(),

                                    Select::make('category_id')
                                        ->label('Kategori')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload(),

                                    Select::make('author_id')
                                        ->label('Yazar')
                                        ->relationship('author', 'name')
                                        ->searchable()
                                        ->preload(),
                                ]),

                            Section::make('Yayın')
                                ->columns(2)
                                ->schema([
                                    Toggle::make('is_active')
                                        ->label('Yayında')
                                        ->default(false)
                                        ->inline(false),

                                    DateTimePicker::make('published_at')
                                        ->label('Yayın tarihi')
                                        ->seconds(false),
                                ]),

                            Section::make('İçerik')
                                ->schema([
                                    Textarea::make('excerpt')
                                        ->label('Özet')
                                        ->rows(3)
                                        ->required()
                                        ->maxLength(300)
                                        ->helperText('Liste kartlarında görünür (max 300).')
                                        ->columnSpanFull(),

                                    RichEditor::make('body')
                                        ->label('Gövde')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('SEO & medya')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Section::make('Görseller')
                                ->columns(2)
                                ->schema([
                                    FileUpload::make('cover_image')
                                        ->label('Kapak')
                                        ->image()
                                        ->directory('guides/covers')
                                        ->imageEditor(),

                                    FileUpload::make('og_image')
                                        ->label('OG görseli')
                                        ->image()
                                        ->directory('guides/og'),
                                ]),

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
                                        ->label('Rehber SSS')
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

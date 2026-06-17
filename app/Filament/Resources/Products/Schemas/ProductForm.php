<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use App\Support\ProductFilters;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([
                Tabs\Tab::make('Genel')->schema([
                    Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('name')
                        ->label('Ürün Adı')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                            $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(Product::class, 'slug', ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('badge')
                        ->label('Rozet (kategori etiketi)')
                        ->maxLength(100),

                    TextInput::make('seller_url')
                        ->label('Satıcı Ürün Linki (Kacmasa)')
                        ->url()
                        ->maxLength(500),

                    TextInput::make('trendyol_url')
                        ->label('Trendyol Ürün Linki (opsiyonel)')
                        ->url()
                        ->maxLength(500),

                    TextInput::make('hepsiburada_url')
                        ->label('Hepsiburada Ürün Linki (opsiyonel)')
                        ->url()
                        ->maxLength(500),

                    CheckboxList::make('tags')
                        ->label('Etiketler / Filtreler')
                        ->options(ProductFilters::LABELS)
                        ->columns(2)
                        ->bulkToggleable(),

                    Toggle::make('is_advantageous')
                        ->label('Avantajlı ürün')
                        ->default(false),

                    Textarea::make('summary')
                        ->label('Kısa Açıklama')
                        ->rows(3)
                        ->maxLength(500),

                    RichEditor::make('description')
                        ->label('Detaylı Açıklama')
                        ->columnSpanFull(),

                    TextInput::make('sort')
                        ->label('Sıralama')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_featured')
                        ->label('Öne Çıkan')
                        ->default(false),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ]),

                Tabs\Tab::make('Görsel & PDF')->schema([
                    FileUpload::make('cover_image')
                        ->label('Kapak Görseli')
                        ->image()
                        ->directory('products/covers')
                        ->maxSize(4096),

                    FileUpload::make('pdf_path')
                        ->label('Katalog PDF')
                        ->acceptedFileTypes(['application/pdf'])
                        ->directory('products/pdfs')
                        ->maxSize(20480),

                    Repeater::make('images')
                        ->label('Galeri Görselleri')
                        ->relationship()
                        ->schema([
                            FileUpload::make('path')
                                ->label('Görsel')
                                ->image()
                                ->directory('products/gallery')
                                ->required()
                                ->maxSize(4096),
                            TextInput::make('alt')
                                ->label('Alt Metin')
                                ->maxLength(255),
                            TextInput::make('sort')
                                ->label('Sıra')
                                ->numeric()
                                ->default(0),
                        ])
                        ->orderColumn('sort')
                        ->collapsible()
                        ->defaultItems(0),
                ]),

                Tabs\Tab::make('Teknik Özellikler')->schema([
                    Repeater::make('specs')
                        ->label('Özellikler')
                        ->relationship()
                        ->schema([
                            TextInput::make('label')
                                ->label('Özellik')
                                ->required()
                                ->maxLength(100),
                            TextInput::make('value')
                                ->label('Değer')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('sort')
                                ->label('Sıra')
                                ->numeric()
                                ->default(0),
                        ])
                        ->orderColumn('sort')
                        ->collapsible()
                        ->defaultItems(0),

                    Repeater::make('useCases')
                        ->label('Kullanım Alanları')
                        ->relationship()
                        ->schema([
                            TextInput::make('title')
                                ->label('Başlık')
                                ->required()
                                ->maxLength(150),
                            Textarea::make('text')
                                ->label('Açıklama')
                                ->rows(2),
                            TextInput::make('icon')
                                ->label('İkon')
                                ->maxLength(100),
                            TextInput::make('sort')
                                ->label('Sıra')
                                ->numeric()
                                ->default(0),
                        ])
                        ->orderColumn('sort')
                        ->collapsible()
                        ->defaultItems(0),
                ]),

                Tabs\Tab::make('SEO')->schema([
                    TextInput::make('seo_title')
                        ->label('SEO Başlık')
                        ->maxLength(70)
                        ->helperText('Önerilen: 50-70 karakter'),

                    Textarea::make('seo_description')
                        ->label('SEO Açıklama')
                        ->rows(3)
                        ->maxLength(160)
                        ->helperText('Önerilen: 120-160 karakter'),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}

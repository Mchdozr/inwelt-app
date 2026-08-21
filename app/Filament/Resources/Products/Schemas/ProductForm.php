<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use App\Support\Money;
use App\Support\ProductFilters;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()
                ->persistTabInQueryString('urun-tab')
                ->tabs([
                    Tabs\Tab::make('Genel')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Kimlik')
                                ->description('Katalogda görünen temel bilgiler.')
                                ->columns(2)
                                ->schema([
                                    Select::make('category_id')
                                        ->label('Kategori')
                                        ->relationship('category', 'name')
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->columnSpan(1),

                                    TextInput::make('badge')
                                        ->label('Rozet')
                                        ->helperText('Kart üzerinde küçük etiket (örn. Yeni).')
                                        ->maxLength(100),

                                    TextInput::make('name')
                                        ->label('Ürün Adı')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                                            $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                        ->columnSpanFull(),

                                    TextInput::make('slug')
                                        ->label('URL slug')
                                        ->required()
                                        ->unique(Product::class, 'slug', ignoreRecord: true)
                                        ->maxLength(255)
                                        ->helperText('Örn: elektrikli-tirnak-kesici-beyaz')
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Durum & sıralama')
                                ->columns(4)
                                ->schema([
                                    TextInput::make('sort')
                                        ->label('Sıra')
                                        ->numeric()
                                        ->default(0)
                                        ->helperText('Küçük = önce.'),

                                    Toggle::make('is_active')
                                        ->label('Aktif')
                                        ->default(true)
                                        ->inline(false),

                                    Toggle::make('is_featured')
                                        ->label('Öne çıkan')
                                        ->default(false)
                                        ->inline(false),

                                    Toggle::make('is_advantageous')
                                        ->label('Avantajlı / fiyat düştü')
                                        ->default(false)
                                        ->inline(false),
                                ]),

                            Section::make('Filtre etiketleri')
                                ->schema([
                                    CheckboxList::make('tags')
                                        ->label('Etiketler')
                                        ->options(ProductFilters::LABELS)
                                        ->columns(3)
                                        ->bulkToggleable()
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Açıklamalar')
                                ->schema([
                                    Textarea::make('summary')
                                        ->label('Kısa açıklama')
                                        ->rows(3)
                                        ->maxLength(500)
                                        ->helperText('Liste ve kartlarda kullanılır (max 500).')
                                        ->columnSpanFull(),

                                    RichEditor::make('description')
                                        ->label('Detaylı açıklama')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('Pazaryeri')
                        ->icon('heroicon-o-shopping-bag')
                        ->schema([
                            Section::make('Buton URL’leri')
                                ->description('Ürün sayfasındaki Kacmasa / Trendyol / Hepsiburada butonlarının gideceği linkler. Kaydettikten sonra sitede hemen yansır.')
                                ->columns(1)
                                ->schema([
                                    TextInput::make('seller_url')
                                        ->label('Kacmasa buton URL')
                                        ->url()
                                        ->maxLength(500)
                                        ->placeholder('https://kacmasa.com/...')
                                        ->helperText('Boşsa Kacmasa butonu gizlenir.')
                                        ->suffixAction(
                                            \Filament\Actions\Action::make('openKacmasa')
                                                ->icon('heroicon-m-arrow-top-right-on-square')
                                                ->url(fn ($get) => filled($get('seller_url')) ? $get('seller_url') : null, shouldOpenInNewTab: true)
                                                ->visible(fn ($get) => filled($get('seller_url')))
                                        ),

                                    TextInput::make('trendyol_url')
                                        ->label('Trendyol buton URL')
                                        ->url()
                                        ->maxLength(500)
                                        ->placeholder('https://www.trendyol.com/...')
                                        ->helperText('Boşsa ürün adına göre Trendyol arama sayfası açılır.')
                                        ->suffixAction(
                                            \Filament\Actions\Action::make('openTrendyol')
                                                ->icon('heroicon-m-arrow-top-right-on-square')
                                                ->url(fn ($get) => filled($get('trendyol_url')) ? $get('trendyol_url') : null, shouldOpenInNewTab: true)
                                                ->visible(fn ($get) => filled($get('trendyol_url')))
                                        ),

                                    TextInput::make('hepsiburada_url')
                                        ->label('Hepsiburada buton URL')
                                        ->url()
                                        ->maxLength(500)
                                        ->placeholder('https://www.hepsiburada.com/...')
                                        ->helperText('Boşsa ürün adına göre Hepsiburada arama sayfası açılır.')
                                        ->suffixAction(
                                            \Filament\Actions\Action::make('openHepsiburada')
                                                ->icon('heroicon-m-arrow-top-right-on-square')
                                                ->url(fn ($get) => filled($get('hepsiburada_url')) ? $get('hepsiburada_url') : null, shouldOpenInNewTab: true)
                                                ->visible(fn ($get) => filled($get('hepsiburada_url')))
                                        ),
                                ]),
                        ]),

                    Tabs\Tab::make('Fiyatlar')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make('Pazaryeri fiyatları')
                                ->description('Sitedeki “En düşük fiyat”, dolu olan alanların minimumudur. Boş bırakılan pazaryeri hesaba katılmaz.')
                                ->columns(4)
                                ->schema([
                                    TextInput::make('price')
                                        ->label('Kacmasa (₺)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.01)
                                        ->prefix('₺')
                                        ->live(onBlur: true),

                                    TextInput::make('trendyol_price')
                                        ->label('Trendyol (₺)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.01)
                                        ->prefix('₺')
                                        ->live(onBlur: true),

                                    TextInput::make('hepsiburada_price')
                                        ->label('Hepsiburada (₺)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.01)
                                        ->prefix('₺')
                                        ->live(onBlur: true),

                                    TextInput::make('compare_at_price')
                                        ->label('Eski / karşılaştırma (₺)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.01)
                                        ->prefix('₺')
                                        ->helperText('Üstü çizili fiyat.'),
                                ]),

                            Section::make('Sync kontrolü')
                                ->columns(2)
                                ->schema([
                                    Toggle::make('prices_locked')
                                        ->label('Fiyatları kilitle')
                                        ->helperText('Açıksa günlük sync bu ürünün fiyatlarını değiştirmez.')
                                        ->default(false)
                                        ->inline(false),

                                    TextInput::make('prices_synced_at')
                                        ->label('Son fiyat sync')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->formatStateUsing(fn ($state) => $state
                                            ? \Illuminate\Support\Carbon::parse($state)->timezone(config('app.timezone'))->format('d.m.Y H:i')
                                            : '—'),

                                    Placeholder::make('lowest_price_preview')
                                        ->label('En düşük fiyat (önizleme)')
                                        ->content(function ($get) {
                                            $prices = collect([
                                                $get('price'),
                                                $get('trendyol_price'),
                                                $get('hepsiburada_price'),
                                            ])->filter(fn ($p) => $p !== null && $p !== '' && (float) $p > 0);

                                            if ($prices->isEmpty()) {
                                                return '—';
                                            }

                                            return new HtmlString('<strong style="font-size:1.1rem">'.e(Money::formatTry((float) $prices->min()) ?? '—').'</strong>');
                                        })
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('Medya')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Section::make('Kapak & PDF')
                                ->columns(2)
                                ->schema([
                                    FileUpload::make('cover_image')
                                        ->label('Kapak görseli')
                                        ->image()
                                        ->directory('products/covers')
                                        ->imageEditor()
                                        ->maxSize(4096),

                                    FileUpload::make('pdf_path')
                                        ->label('Katalog PDF')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->directory('products/pdfs')
                                        ->maxSize(20480),
                                ]),

                            Section::make('Galeri')
                                ->description('Ürün detay galerisi. Sürükleyerek sıralayabilirsiniz.')
                                ->schema([
                                    Repeater::make('images')
                                        ->label('Görseller')
                                        ->relationship()
                                        ->schema([
                                            FileUpload::make('path')
                                                ->label('Görsel')
                                                ->image()
                                                ->directory('products/gallery')
                                                ->required()
                                                ->maxSize(4096)
                                                ->columnSpan(2),
                                            TextInput::make('alt')
                                                ->label('Alt metin')
                                                ->maxLength(255),
                                            TextInput::make('sort')
                                                ->label('Sıra')
                                                ->numeric()
                                                ->default(0),
                                        ])
                                        ->columns(4)
                                        ->orderColumn('sort')
                                        ->collapsible()
                                        ->cloneable()
                                        ->defaultItems(0)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('Özellikler')
                        ->icon('heroicon-o-list-bullet')
                        ->schema([
                            Section::make('Teknik özellikler')
                                ->schema([
                                    Repeater::make('specs')
                                        ->label('Özellik satırları')
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
                                        ->columns(3)
                                        ->orderColumn('sort')
                                        ->collapsible()
                                        ->cloneable()
                                        ->defaultItems(0)
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Kullanım alanları')
                                ->schema([
                                    Repeater::make('useCases')
                                        ->label('Kullanım kartları')
                                        ->relationship()
                                        ->schema([
                                            TextInput::make('title')
                                                ->label('Başlık')
                                                ->required()
                                                ->maxLength(150)
                                                ->columnSpan(2),
                                            TextInput::make('icon')
                                                ->label('İkon')
                                                ->maxLength(100)
                                                ->helperText('Heroicon / CSS sınıfı'),
                                            TextInput::make('sort')
                                                ->label('Sıra')
                                                ->numeric()
                                                ->default(0),
                                            Textarea::make('text')
                                                ->label('Açıklama')
                                                ->rows(2)
                                                ->columnSpanFull(),
                                        ])
                                        ->columns(4)
                                        ->orderColumn('sort')
                                        ->collapsible()
                                        ->cloneable()
                                        ->defaultItems(0)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            Section::make('Arama motoru')
                                ->columns(2)
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

                                    FileUpload::make('og_image')
                                        ->label('OG / sosyal görsel')
                                        ->image()
                                        ->directory('products/og')
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Ürün kodları & puan')
                                ->columns(4)
                                ->schema([
                                    TextInput::make('sku')->label('SKU')->maxLength(80),
                                    TextInput::make('gtin13')->label('GTIN-13')->maxLength(13),
                                    TextInput::make('rating_value')
                                        ->label('Puan')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(5)
                                        ->step(0.1),
                                    TextInput::make('rating_count')
                                        ->label('Değerlendirme sayısı')
                                        ->numeric()
                                        ->minValue(0),
                                ]),

                            Section::make('İlgili içerik')
                                ->schema([
                                    Select::make('related_guide_slugs')
                                        ->label('İlgili rehberler')
                                        ->multiple()
                                        ->searchable()
                                        ->options(fn () => \App\Models\Guide::query()->orderBy('title')->pluck('title', 'slug')->all())
                                        ->columnSpanFull(),

                                    Repeater::make('faq_items')
                                        ->label('Ürün SSS')
                                        ->schema([
                                            TextInput::make('question')->label('Soru')->required()->columnSpanFull(),
                                            Textarea::make('answer')->label('Cevap')->required()->rows(3)->columnSpanFull(),
                                        ])
                                        ->columns(1)
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

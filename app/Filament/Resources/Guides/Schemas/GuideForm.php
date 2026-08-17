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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([
                Tabs\Tab::make('Genel')->schema([
                    TextInput::make('title')
                        ->label('Başlık')
                        ->required()
                        ->maxLength(180)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                            $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Select::make('category_id')->label('Kategori')->relationship('category', 'name')->searchable()->preload(),
                    Select::make('author_id')->label('Yazar')->relationship('author', 'name')->searchable()->preload(),
                    Textarea::make('excerpt')->label('Özet')->rows(3)->required()->maxLength(300),
                    RichEditor::make('body')->label('İçerik')->required()->columnSpanFull(),
                    Toggle::make('is_active')->label('Yayında')->default(false),
                    DateTimePicker::make('published_at')->label('Yayın tarihi'),
                ]),
                Tabs\Tab::make('SEO')->schema([
                    FileUpload::make('cover_image')->image()->directory('guides/covers'),
                    FileUpload::make('og_image')->image()->directory('guides/og'),
                    TextInput::make('seo_title')->maxLength(70),
                    Textarea::make('seo_description')->rows(3)->maxLength(160),
                    Repeater::make('faq_items')->label('SSS')->schema([
                        TextInput::make('question')->label('Soru')->required(),
                        Textarea::make('answer')->label('Cevap')->required(),
                    ])->defaultItems(0)->collapsible(),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Guides;

use App\Filament\Resources\Guides\Pages\CreateGuide;
use App\Filament\Resources\Guides\Pages\EditGuide;
use App\Filament\Resources\Guides\Pages\ListGuides;
use App\Filament\Resources\Guides\Schemas\GuideForm;
use App\Filament\Resources\Guides\Tables\GuidesTable;
use App\Models\Guide;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class GuideResource extends Resource
{
    protected static ?string $model = Guide::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-book-open';
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'İçerik';
    }

    public static function getNavigationLabel(): string
    {
        return 'Rehberler';
    }

    public static function getModelLabel(): string
    {
        return 'Rehber';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Rehberler';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return GuideForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuidesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGuides::route('/'),
            'create' => CreateGuide::route('/create'),
            'edit' => EditGuide::route('/{record}/edit'),
        ];
    }
}

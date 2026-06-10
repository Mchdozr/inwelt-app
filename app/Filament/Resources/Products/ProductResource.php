<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use Filament\Resources\Resource;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-cube';
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Ürün Yönetimi';
    }

    public static function getNavigationLabel(): string
    {
        return 'Ürünler';
    }

    public static function getModelLabel(): string
    {
        return 'Ürün';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Ürünler';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Products\Tables;

use App\Support\Money;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Görsel')
                    ->width(60)
                    ->height(40),

                TextColumn::make('name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge(),

                TextColumn::make('lowest_price')
                    ->label('En düşük fiyat')
                    ->state(fn ($record) => Money::formatTry($record->lowestRawPrice()) ?? '—')
                    ->sortable(query: function ($query, string $direction) {
                        return $query->orderByRaw(
                            'LEAST(
                                COALESCE(NULLIF(price, 0), 999999999),
                                COALESCE(NULLIF(trendyol_price, 0), 999999999),
                                COALESCE(NULLIF(hepsiburada_price, 0), 999999999)
                            ) '.$direction
                        );
                    }),

                IconColumn::make('prices_locked')
                    ->label('Fiyat kilit')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('badge')
                    ->label('Rozet')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_featured')
                    ->label('Öne Çıkan')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort')
                    ->label('Sıra')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Güncellendi')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->defaultSort('sort')
            ->actions([
                EditAction::make()->label('Düzenle'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Seçilenleri Sil'),
                ]),
            ]);
    }
}

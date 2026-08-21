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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Görsel')
                    ->width(56)
                    ->height(40)
                    ->square(),

                TextColumn::make('name')
                    ->label('Ürün')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn ($record) => $record->slug),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->toggleable(),

                TextColumn::make('lowest_price')
                    ->label('En düşük')
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

                TextColumn::make('price')
                    ->label('Kacmasa')
                    ->formatStateUsing(fn ($state) => Money::formatTry($state) ?? '—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('trendyol_price')
                    ->label('Trendyol')
                    ->formatStateUsing(fn ($state) => Money::formatTry($state) ?? '—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('hepsiburada_price')
                    ->label('Hepsiburada')
                    ->formatStateUsing(fn ($state) => Money::formatTry($state) ?? '—')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('prices_locked')
                    ->label('Kilit')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('prices_synced_at')
                    ->label('Son sync')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_featured')
                    ->label('Öne çıkan')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort')
                    ->label('Sıra')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Güncellendi')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_active')->label('Aktif'),
                TernaryFilter::make('is_featured')->label('Öne çıkan'),
                TernaryFilter::make('prices_locked')->label('Fiyat kilitli'),
            ])
            ->defaultSort('sort')
            ->striped()
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

<?php

namespace App\Filament\Resources\Guides\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GuidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn ($record) => $record->slug),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('author.name')
                    ->label('Yazar')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Yayında')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Yayın')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Güncellendi')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Yayında'),
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->defaultSort('published_at', 'desc')
            ->striped()
            ->recordActions([
                EditAction::make()->label('Düzenle'),
            ]);
    }
}

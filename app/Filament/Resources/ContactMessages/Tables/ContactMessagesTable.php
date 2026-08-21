<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email),

                TextColumn::make('phone')
                    ->label('Telefon')
                    ->toggleable(),

                TextColumn::make('subject')
                    ->label('Konu')
                    ->searchable()
                    ->limit(40)
                    ->wrap(),

                TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_read')
                    ->label('Okundu')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Gönderildi')
                    ->since()
                    ->sortable()
                    ->description(fn ($record) => $record->created_at?->format('d.m.Y H:i')),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Durum')
                    ->trueLabel('Okunmuş')
                    ->falseLabel('Okunmamış'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->actions([
                EditAction::make()->label('Görüntüle'),
            ]);
    }
}

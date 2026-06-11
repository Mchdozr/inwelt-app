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
                    ->sortable(),

                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telefon'),

                TextColumn::make('subject')
                    ->label('Konu')
                    ->limit(40),

                TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(60)
                    ->wrap(),

                IconColumn::make('is_read')
                    ->label('Okundu')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Gönderildi')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Durum')
                    ->trueLabel('Okunmuş')
                    ->falseLabel('Okunmamış'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make()->label('Görüntüle'),
            ]);
    }
}

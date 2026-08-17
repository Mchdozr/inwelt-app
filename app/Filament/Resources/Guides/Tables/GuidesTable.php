<?php

namespace App\Filament\Resources\Guides\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GuidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Başlık')->searchable()->sortable(),
                TextColumn::make('author.name')->label('Yazar'),
                IconColumn::make('is_active')->label('Yayında')->boolean(),
                TextColumn::make('published_at')->label('Yayın')->dateTime('d.m.Y')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}

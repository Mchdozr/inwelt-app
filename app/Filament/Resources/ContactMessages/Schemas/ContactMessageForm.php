<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Gönderen')
                ->columns(3)
                ->schema([
                    TextInput::make('name')->label('Ad Soyad')->disabled(),
                    TextInput::make('email')->label('E-posta')->disabled(),
                    TextInput::make('phone')->label('Telefon')->disabled(),
                ]),

            Section::make('Mesaj')
                ->schema([
                    TextInput::make('subject')->label('Konu')->disabled()->columnSpanFull(),
                    Textarea::make('message')->label('İçerik')->disabled()->rows(8)->columnSpanFull(),
                    Placeholder::make('created_at_info')
                        ->label('Gönderim zamanı')
                        ->content(fn ($record) => $record?->created_at
                            ? $record->created_at->timezone(config('app.timezone'))->format('d.m.Y H:i')
                            : '—'),
                ]),

            Section::make('İşlem')
                ->schema([
                    Toggle::make('is_read')
                        ->label('Okundu olarak işaretle')
                        ->inline(false),
                ]),
        ]);
    }
}

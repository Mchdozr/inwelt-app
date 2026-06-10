<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Pages\EditContactMessage;
use App\Models\ContactMessage;
use Filament\Resources\Resource;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-envelope';
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Mesajlar';
    }

    public static function getNavigationLabel(): string
    {
        return 'İletişim Mesajları';
    }

    public static function getModelLabel(): string
    {
        return 'Mesaj';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Mesajlar';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_read', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),
            'edit' => EditContactMessage::route('/{record}/edit'),
        ];
    }
}

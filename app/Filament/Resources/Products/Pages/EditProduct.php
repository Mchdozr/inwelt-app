<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Support\Money;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $this->record->name;
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        $lowest = Money::formatTry($this->record->lowestRawPrice()) ?? 'fiyat yok';
        $status = $this->record->is_active ? 'Aktif' : 'Pasif';
        $lock = $this->record->prices_locked ? ' · Fiyat kilitli' : '';
        $synced = $this->record->prices_synced_at
            ? ' · Sync: '.$this->record->prices_synced_at->timezone(config('app.timezone'))->format('d.m.Y H:i')
            : '';

        return "En düşük: {$lowest} · {$status}{$lock}{$synced}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewOnSite')
                ->label('Sitede gör')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => route('products.show', $this->record->slug))
                ->openUrlInNewTab()
                ->color('gray'),
            DeleteAction::make(),
        ];
    }
}

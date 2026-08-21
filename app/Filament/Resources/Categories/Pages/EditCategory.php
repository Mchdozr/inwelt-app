<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $this->record->name;
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        $status = $this->record->is_active ? 'Aktif' : 'Pasif';
        $count = $this->record->products()->count();

        return "{$status} · {$count} ürün · sıra: {$this->record->sort}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewOnSite')
                ->label('Sitede gör')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => route('products.category', $this->record->slug))
                ->openUrlInNewTab()
                ->color('gray'),
            DeleteAction::make(),
        ];
    }
}

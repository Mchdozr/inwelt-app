<?php

namespace App\Filament\Resources\Guides\Pages;

use App\Filament\Resources\Guides\GuideResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGuide extends EditRecord
{
    protected static string $resource = GuideResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $this->record->title;
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        $status = $this->record->is_active ? 'Yayında' : 'Taslak';
        $date = $this->record->published_at
            ? $this->record->published_at->timezone(config('app.timezone'))->format('d.m.Y H:i')
            : 'tarih yok';

        return "{$status} · Yayın: {$date}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewOnSite')
                ->label('Sitede gör')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => route('guides.show', $this->record->slug))
                ->openUrlInNewTab()
                ->color('gray')
                ->visible(fn () => filled($this->record->slug)),
            DeleteAction::make(),
        ];
    }
}

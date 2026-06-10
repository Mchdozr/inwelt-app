<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.site-settings';

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return 'Site Ayarları';
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Site Ayarları';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public function mount(): void
    {
        $keys = ['site_phone', 'site_email', 'site_address', 'social_linkedin', 'social_instagram', 'social_youtube'];
        $values = [];

        foreach ($keys as $key) {
            $values[$key] = Setting::get($key);
        }

        $this->form->fill($values);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('İletişim Bilgileri')->schema([
                    TextInput::make('site_phone')->label('Telefon')->tel(),
                    TextInput::make('site_email')->label('E-posta')->email(),
                    TextInput::make('site_address')->label('Adres'),
                ]),

                Section::make('Sosyal Medya')->schema([
                    TextInput::make('social_linkedin')->label('LinkedIn')->url(),
                    TextInput::make('social_instagram')->label('Instagram')->url(),
                    TextInput::make('social_youtube')->label('YouTube')->url(),
                ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Kaydet')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }

        Notification::make()
            ->title('Ayarlar kaydedildi.')
            ->success()
            ->send();
    }
}

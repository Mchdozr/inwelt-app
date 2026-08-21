<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettings extends Page
{
    public ?array $data = [];

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-cog-6-tooth';
    }

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
        $keys = [
            'site_phone', 'site_email', 'site_address', 'whatsapp_phone',
            'social_linkedin', 'social_instagram', 'social_youtube',
            'google_site_verification', 'bing_site_verification', 'indexnow_key',
        ];
        $values = [];

        foreach ($keys as $key) {
            $values[$key] = Setting::get($key);
        }

        $this->form->fill($values);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('İletişim Bilgileri')
                    ->description('Sitede ve WhatsApp yönlendirmesinde kullanılır.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_phone')->label('Telefon (yalnızca dahili)')->tel()->helperText('Sitede gösterilmez; WhatsApp yedek numarası olarak kullanılır.'),
                        TextInput::make('whatsapp_phone')->label('WhatsApp numarası')->tel()->helperText('Müşterilerin yönlendirildiği WhatsApp hattı. Boşsa dahili telefon kullanılır.'),
                        TextInput::make('site_email')->label('E-posta')->email(),
                        TextInput::make('site_address')->label('Adres'),
                    ]),

                Section::make('SEO & Analitik')
                    ->columns(2)
                    ->schema([
                        TextInput::make('google_site_verification')->label('Google Search Console doğrulama kodu'),
                        TextInput::make('bing_site_verification')->label('Bing Webmaster doğrulama kodu'),
                        TextInput::make('indexnow_key')->label('IndexNow anahtarı')->helperText('Boşsa kaydettiğinizde otomatik üretilir.')->columnSpanFull(),
                    ]),

                Section::make('Sosyal Medya')
                    ->columns(3)
                    ->schema([
                        TextInput::make('social_linkedin')->label('LinkedIn')->url(),
                        TextInput::make('social_instagram')->label('Instagram')->url(),
                        TextInput::make('social_youtube')->label('YouTube')->url(),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->key('form-actions'),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Kaydet')
                ->submit('save'),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (empty($data['indexnow_key'])) {
            $data['indexnow_key'] = \App\Support\IndexNow::generateKey();
            $this->form->fill($data);
        }

        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }

        Notification::make()
            ->title('Ayarlar kaydedildi.')
            ->success()
            ->send();
    }
}

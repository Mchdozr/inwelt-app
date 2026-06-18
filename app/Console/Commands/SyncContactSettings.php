<?php

namespace App\Console\Commands;

use App\Support\SiteContact;
use Illuminate\Console\Command;

class SyncContactSettings extends Command
{
    protected $signature = 'inwelt:sync-contact-settings';

    protected $description = 'Eski iletişim ayarlarını güncel telefon ve e-posta ile senkronize eder';

    public function handle(): int
    {
        SiteContact::syncSettings();

        $this->info('Telefon: '.SiteContact::phone());
        $this->info('E-posta: '.SiteContact::email());

        return self::SUCCESS;
    }
}

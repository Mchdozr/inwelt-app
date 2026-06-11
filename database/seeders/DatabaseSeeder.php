<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedSettings();
        Artisan::call('inwelt:rebuild-catalog');
    }

    private function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@inwelt.com.tr'],
            ['name' => 'Inwelt Admin', 'password' => Hash::make('inwelt2026')]
        );
    }

    private function seedSettings(): void
    {
        $defaults = [
            'site_phone' => '+90 850 000 00 00',
            'site_email' => 'info@inwelt.com.tr',
            'site_address' => 'İstanbul, Türkiye',
            'social_linkedin' => 'https://linkedin.com',
            'social_instagram' => 'https://instagram.com',
            'social_youtube' => 'https://youtube.com',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

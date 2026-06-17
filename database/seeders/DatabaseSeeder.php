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
            [
                'name' => 'Inwelt Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'inwelt2026')),
            ]
        );
    }

    private function seedSettings(): void
    {
        $defaults = [
            'site_phone' => '+90 543 359 40 02',
            'site_email' => 'inwelt@inwelt.com.tr',
            'site_address' => 'İstanbul, Türkiye',
            'social_linkedin' => 'https://linkedin.com',
            'social_instagram' => 'https://www.instagram.com/inwelt.com.tr/',
            'social_youtube' => 'https://youtube.com',
            'whatsapp_phone' => '+90 543 359 40 02',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

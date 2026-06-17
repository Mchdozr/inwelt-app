<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RotateAdminPassword extends Command
{
    protected $signature = 'inwelt:rotate-admin-password
                            {--password= : Yeni şifre (boşsa ADMIN_PASSWORD env veya rastgele)}
                            {--email=admin@inwelt.com.tr : Admin e-posta}';

    protected $description = 'Filament admin kullanıcı şifresini güvenli şekilde günceller';

    public function handle(): int
    {
        $email = (string) $this->option('email');
        $password = $this->option('password') ?: env('ADMIN_PASSWORD');

        if (! $password) {
            $password = Str::password(16);
            $this->warn('Rastgele şifre üretildi — güvenli bir yere kaydedin:');
            $this->line($password);
        }

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $this->error("Kullanıcı bulunamadı: {$email}");

            return self::FAILURE;
        }

        $user->update(['password' => Hash::make($password)]);
        $this->info("{$email} şifresi güncellendi.");

        return self::SUCCESS;
    }
}

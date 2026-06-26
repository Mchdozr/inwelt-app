<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $row = DB::table('settings')->where('key', 'site_address')->first();

        if ($row === null) {
            DB::table('settings')->insert([
                'key' => 'site_address',
                'value' => \App\Support\SiteContact::ADDRESS,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        if ((string) $row->value === 'İstanbul, Türkiye'
            || trim((string) $row->value) === '') {
            DB::table('settings')->where('key', 'site_address')->update([
                'value' => \App\Support\SiteContact::ADDRESS,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Geri alınmaz.
    }
};

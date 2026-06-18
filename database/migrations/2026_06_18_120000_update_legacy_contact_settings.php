<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_PHONE = '+90 543 359 40 02';

    private const NEW_EMAIL = 'inwelt@inwelt.com.tr';

  /** @var list<string> */
    private const LEGACY_PHONE_DIGITS = [
        '908500000000',
        '905498002510',
        '8500000000',
        '5498002510',
    ];

    public function up(): void
    {
        foreach (['site_phone', 'whatsapp_phone'] as $key) {
            $row = DB::table('settings')->where('key', $key)->first();

            if ($row === null) {
                continue;
            }

            $digits = preg_replace('/\D+/', '', (string) $row->value) ?? '';

            if (in_array($digits, self::LEGACY_PHONE_DIGITS, true)) {
                DB::table('settings')->where('key', $key)->update(['value' => self::NEW_PHONE]);
            }
        }

        $emailRow = DB::table('settings')->where('key', 'site_email')->first();

        if ($emailRow !== null && strtolower((string) $emailRow->value) === 'info@inwelt.com.tr') {
            DB::table('settings')->where('key', 'site_email')->update(['value' => self::NEW_EMAIL]);
        }
    }

    public function down(): void
    {
        // Geri alınmaz — eski iletişim bilgileri geçersiz.
    }
};

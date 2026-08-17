<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class IndexNow
{
    public static function key(): ?string
    {
        $key = config('seo.indexnow_key') ?: \App\Models\Setting::get('indexnow_key');

        return SeoEnv::indexNowKey(is_string($key) ? $key : null) ?? SeoEnv::INDEXNOW_DEFAULT;
    }

    /**
     * @param  list<string>  $urls
     * @return array{host: string, key: string, keyLocation: string, urlList: list<string>}
     */
    public static function payload(array $urls): array
    {
        $key = self::key() ?? '';
        $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'inwelt.com.tr';

        return [
            'host' => $host,
            'key' => $key,
            'keyLocation' => rtrim((string) config('app.url'), '/').'/'.$key.'.txt',
            'urlList' => array_values($urls),
        ];
    }

    public static function ping(string $url): void
    {
        $key = self::key();

        if ($key === null) {
            return;
        }

        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        try {
            Http::timeout(8)
                ->acceptJson()
                ->put('https://api.indexnow.org/indexnow', self::payload([$url]));
        } catch (\Throwable) {
            // IndexNow is best-effort; never block a save.
        }
    }

    public static function generateKey(): string
    {
        return Str::lower(Str::random(32));
    }
}

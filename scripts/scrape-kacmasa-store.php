<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$all = [];

foreach ([1, 2] as $page) {
    $url = $page === 1
        ? 'https://kacmasa.com/magaza/NWELT'
        : 'https://kacmasa.com/magaza/NWELT?page=2';

    $response = Http::withoutVerifying()
        ->timeout(120)
        ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'])
        ->get($url);

    if (! $response->successful()) {
        fwrite(STDERR, "Failed page {$page}: {$response->status()}\n");
        continue;
    }

    $html = $response->body();
    file_put_contents(__DIR__."/kacmasa-page-{$page}.html", $html);

    if (preg_match_all('#<div class="caption">\s*<h4><a href="([^"]+)">([^<]+)</a>#s', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $href = html_entity_decode($match[1]);
            if (! str_starts_with($href, 'http')) {
                $href = 'https://kacmasa.com/'.ltrim($href, '/');
            }
            $all[$href] = trim(html_entity_decode($match[2]));
        }
    }

    if (preg_match_all('#<h4><a href="([^"]+)">([^<]+)</a>#', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $href = html_entity_decode($match[1]);
            if (! str_starts_with($href, 'http')) {
                $href = 'https://kacmasa.com/'.ltrim($href, '/');
            }
            if (str_contains($href, 'kacmasa.com/') && ! str_contains($href, 'magaza')) {
                $all[$href] = trim(html_entity_decode($match[2]));
            }
        }
    }
}

echo json_encode($all, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).PHP_EOL;

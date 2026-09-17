<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Http;

$products = [
    'rc-forklift-oyuncak' => 'https://kacmasa.com/rc-forklift-oyuncagi-uzaktan-kumandali-124-olcekli-sarjli-is-makinesi-sesli-isikli-yuk-kaldirma-fonksiyonlu-erkek-cocuk-oyuncagi-hediye',
    'otomatik-kart-karistirici' => 'https://kacmasa.com/otomatik-kart-karistirici-ve-dagitici-360-doner-kart-makinesi-kablosuz-kumandali-2-8-kisilik-poker-uno-texas-holdem-ve-blackjack-uyumlu',
    'yapay-zeka-robot-kopek' => 'https://kacmasa.com/yapay-zeka-destekli-robot-kopek-oyuncagi-ses-kontrollu-uzaktan-kumandali-usb-sarjli-dans-ve-muzik-ozellikli-akilli-robot-kopek',
    'pop-mart-skullpanda-winter' => 'https://kacmasa.com/inwelt-pop-mart-skullpanda-winter-symphony-series-plush-single-blind-box',
    'pop-mart-labubu-blind-box' => 'https://kacmasa.com/pop-mart-labubu-the-monsters-big-into-energy-series-blind-box',
    'stitch-labubu-pendant' => 'https://kacmasa.com/stitch-labubu-adventure-series-vinyl-plush-pendant6882167',
    'smart-scalp-massager' => 'https://kacmasa.com/inwelt-smart-scalp-massager-derinlemesine-sac-derisi-masaj-cihazi',
    'rc-paletli-vinc-8-kanal' => 'https://kacmasa.com/inwelt-uzaktan-kumandali-sarjli-8-kanalli-paletli-vinc-oyuncak',
    'akilli-tic-tac-toe' => 'https://kacmasa.com/inwelt-akilli-tic-tac-to-2-kisilik-oyunlar-eglenceli-ve-egitici-bir-zeka-oyunu-deneyimi-sos',
    'gizli-kamera-dedektoru' => 'https://kacmasa.com/inwelt-gizli-kamera-ve-sinyal-dedektoru-guvenlik-tarayici',
    'katlanabilir-bluetooth-klavye' => 'https://kacmasa.com/turkce-q-katlanabilir-bluetooth-klavye-touchpadli-type-c-sarjli-ios-android-windows-uyumlu-tasinabilir-tasarim',
    'dijital-bagaj-tartisi' => 'https://kacmasa.com/inwelt-dijital-bagaj-tartisi-50-kg-lcd-ekranli-hassas-valiz-terazisi-dara-ozellikli-seyahat-icin-tasinabilir',
    'led-isikli-topac-oyuncak' => 'https://kacmasa.com/inwelt-led-isikli-topac-oyuncak-donen-gyroskop-fidget-spinner-stres-giderici-cocuk-oyuncagi',
    'mini-drone-hd-kamera' => 'https://kacmasa.com/inwelt-mini-drone-hd-kamera-fotograf-ve-video-cekimi-optical-hover-destekli',
];

$headers = [
    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Accept' => 'text/html,application/xhtml+xml,image/webp,image/apng,*/*;q=0.8',
    'Accept-Language' => 'tr-TR,tr;q=0.9',
];

if (isset($argv[1])) {
    $only = array_slice($argv, 1);
    $products = array_intersect_key($products, array_flip($only));
}

$failed = [];

foreach ($products as $slug => $url) {
    $product = Product::query()->where('slug', $slug)->first();
    if (! $product) {
        echo "MISSING {$slug}\n";
        $failed[] = $slug;
        continue;
    }

    echo "↓ {$slug}\n";

    $response = Http::withoutVerifying()->timeout(120)->withHeaders($headers)->get($url);
    if (! $response->successful()) {
        echo "  HTTP {$response->status()}\n";
        $failed[] = $slug;
        continue;
    }

    $images = extractGallery($response->body());
    if ($images === []) {
        echo "  no gallery\n";
        $failed[] = $slug;
        continue;
    }

    $dir = storage_path("app/public/products/{$slug}");
    if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
        echo "  mkdir fail\n";
        $failed[] = $slug;
        continue;
    }

    foreach (glob($dir.'/g*.webp') ?: [] as $old) {
        unlink($old);
    }

    $saved = [];
    foreach (array_values($images) as $index => $relativePath) {
        $imageUrl = 'https://kacmasa.com/image/cache/wkseller/'.$relativePath;
        $fileResponse = Http::withoutVerifying()->timeout(90)->withHeaders($headers)->get($imageUrl);
        if (! $fileResponse->successful() || strlen($fileResponse->body()) < 2000) {
            echo "  skip {$relativePath} HTTP ".$fileResponse->status()."\n";
            continue;
        }

        $filename = 'g'.($index + 1).'.webp';
        file_put_contents($dir.'/'.$filename, $fileResponse->body());
        $saved[] = 'products/'.$slug.'/'.$filename;
    }

    if ($saved === []) {
        echo "  download empty\n";
        $failed[] = $slug;
        continue;
    }

    $product->cover_image = $saved[0];
    $product->seller_url = $url;
    $product->save();

    $product->images()->delete();
    foreach ($saved as $i => $path) {
        $product->images()->create([
            'path' => $path,
            'alt' => $product->name,
            'sort' => $i,
        ]);
    }

    echo '  '.count($saved)." görsel\n";
}

if ($failed !== []) {
    fwrite(STDERR, 'FAILED: '.implode(', ', $failed).PHP_EOL);
    exit(1);
}

echo "OK\n";

/**
 * @return array<int, string>
 */
function extractGallery(string $html): array
{
    $section = $html;
    if (preg_match('#<div class="product-left">(.*?)<div class="product-right"#s', $html, $match)) {
        $section = $match[1];
    }

    preg_match_all('#image/cache/wkseller/(\d+/[^"\'\s]+?\.(?:webp|jpg|jpeg|png))#i', $section, $matches);

    $picked = [];
    foreach (array_unique($matches[1] ?? []) as $path) {
        if (! preg_match('/_(\d+)-(?:\d{10,}-)?(\d+)x(\d+)/', $path, $parts)) {
            continue;
        }

        $idx = (int) $parts[1];
        $score = match (true) {
            str_contains($path, '1200x1800') => 100,
            str_contains($path, '900x1350') => 90,
            str_contains($path, '600x900') => 70,
            default => 0,
        };

        if ($score === 0) {
            continue;
        }

        if (! isset($picked[$idx]) || $score > $picked[$idx]['score']) {
            $picked[$idx] = ['path' => $path, 'score' => $score];
        }
    }

    ksort($picked);

    return array_map(fn (array $item) => $item['path'], $picked);
}

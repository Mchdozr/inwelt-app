<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

$sellerUrls = [
    'gizli-kamera-dedektoru' => 'https://kacmasa.com/inwelt-gizli-kamera-ve-sinyal-dedektoru-guvenlik-tarayici',
    'katlanabilir-bluetooth-klavye' => 'https://kacmasa.com/turkce-q-katlanabilir-bluetooth-klavye-touchpadli-type-c-sarjli-ios-android-windows-uyumlu-tasinabilir-tasarim',
];

$slugs = [
    'rc-forklift-oyuncak',
    'otomatik-kart-karistirici',
    'yapay-zeka-robot-kopek',
    'pop-mart-skullpanda-winter',
    'pop-mart-labubu-blind-box',
    'stitch-labubu-pendant',
    'smart-scalp-massager',
    'rc-paletli-vinc-8-kanal',
    'akilli-tic-tac-toe',
    'gizli-kamera-dedektoru',
    'katlanabilir-bluetooth-klavye',
    'dijital-bagaj-tartisi',
    'led-isikli-topac-oyuncak',
    'mini-drone-hd-kamera',
];

foreach ($slugs as $slug) {
    $product = Product::query()->where('slug', $slug)->first();
    if (! $product) {
        echo "MISSING {$slug}\n";
        continue;
    }

    $dir = storage_path("app/public/products/{$slug}");
    $files = glob($dir.'/g*.webp') ?: [];
    natsort($files);
    $files = array_values($files);

    if ($files === []) {
        echo "NO FILES {$slug}\n";
        continue;
    }

    $paths = array_map(fn (string $file) => 'products/'.$slug.'/'.basename($file), $files);

    $product->cover_image = $paths[0];
    if (isset($sellerUrls[$slug])) {
        $product->seller_url = $sellerUrls[$slug];
    }
    $product->save();

    $product->images()->delete();
    foreach ($paths as $i => $path) {
        $product->images()->create([
            'path' => $path,
            'alt' => $product->name,
            'sort' => $i,
        ]);
    }

    echo "{$slug} ".count($paths)." görsel\n";
}

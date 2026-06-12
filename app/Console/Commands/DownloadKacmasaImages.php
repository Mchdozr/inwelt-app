<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadKacmasaImages extends Command
{
    protected $signature = 'inwelt:download-kacmasa-images {--slug= : Tek ürün slug}';

    protected $description = 'Kacmasa ürün sayfalarından galeri görsellerini indirir';

    public function handle(): int
    {
        $query = Product::query()->whereNotNull('seller_url');

        if ($slug = $this->option('slug')) {
            $query->where('slug', $slug);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $this->warn('İndirilecek ürün bulunamadı. Önce inwelt:rebuild-catalog çalıştırın.');

            return self::FAILURE;
        }

        foreach ($products as $product) {
            $this->downloadForProduct($product);
        }

        return self::SUCCESS;
    }

    private function downloadForProduct(Product $product): void
    {
        $dir = storage_path("app/public/products/{$product->slug}");
        $existing = glob("{$dir}/g*.webp") ?: [];

        if (count($existing) > 0) {
            $this->line("  = {$product->slug} (zaten var)");

            return;
        }

        $this->info("  ↓ {$product->slug}");

        $response = Http::withoutVerifying()
            ->timeout(120)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'])
            ->get($product->seller_url);

        if (! $response->successful()) {
            $this->error("    HTTP {$response->status()}");

            return;
        }

        $match = $this->matchPattern($product->seller_url);
        $images = $this->extractGalleryImages($response->body(), $match);

        if ($images === []) {
            $this->warn('    Görsel bulunamadı');

            return;
        }

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach (array_values($images) as $index => $path) {
            $url = "https://kacmasa.com/image/cache/wkseller/711/{$path}";
            $fileResponse = Http::withoutVerifying()->timeout(90)->get($url);

            if (! $fileResponse->successful()) {
                continue;
            }

            $filename = 'g'.($index + 1).'.webp';
            file_put_contents("{$dir}/{$filename}", $fileResponse->body());
        }

        $downloaded = glob("{$dir}/g*.webp") ?: [];
        natsort($downloaded);
        $downloaded = array_values($downloaded);

        if ($downloaded === []) {
            return;
        }

        $coverPath = 'products/'.$product->slug.'/'.basename($downloaded[0]);
        $product->cover_image = $coverPath;
        $product->save();

        $product->images()->delete();

        foreach ($downloaded as $i => $file) {
            $product->images()->create([
                'path' => 'products/'.$product->slug.'/'.basename($file),
                'alt' => $product->name,
                'sort' => $i,
            ]);
        }

        $this->line('    '.count($downloaded).' görsel kaydedildi');
    }

    /**
     * @return array<int, string>
     */
    private function extractGalleryImages(string $html, string $matchPattern): array
    {
        preg_match_all('#image/cache/wkseller/711/([^"\\s]+\\.(?:webp|jpg|jpeg|png))#i', $html, $matches);

        $picked = [];

        foreach (array_unique($matches[1] ?? []) as $path) {
            if (! preg_match('/'.$matchPattern.'/i', $path)) {
                continue;
            }

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

    private function matchPattern(string $sellerUrl): string
    {
        $path = parse_url($sellerUrl, PHP_URL_PATH) ?? '';
        $slug = trim($path, '/');
        $parts = array_values(array_filter(explode('-', $slug)));

        $needles = array_slice($parts, 0, min(4, count($parts)));

        return implode('|', array_map(fn (string $p) => preg_quote($p, '/'), $needles));
    }
}

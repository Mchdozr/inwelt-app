<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\KacmasaCatalogParser;
use App\Services\MarketplacePriceParser;
use App\Support\ProductMarketplace;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncMarketplacePrices extends Command
{
    protected $signature = 'inwelt:sync-marketplace-prices
                            {--pages=3 : Kacmasa liste sayfası sayısı}
                            {--dry-run : Veritabanına yazmadan önizle}';

    protected $description = 'Kacmasa, Trendyol ve Hepsiburada fiyatlarını günceller';

    public function handle(
        KacmasaCatalogParser $kacmasaParser,
        MarketplacePriceParser $priceParser,
    ): int {
        $dryRun = (bool) $this->option('dry-run');
        $syncedAt = now();

        $kacmasaUpdated = $this->syncKacmasaCatalogPrices($kacmasaParser, $syncedAt, $dryRun);
        $kacmasaUpdated += $this->syncKacmasaProductPagePrices($priceParser, $syncedAt, $dryRun);
        $trendyolUpdated = $this->syncProductPagePrices('trendyol', $priceParser, $syncedAt, $dryRun);
        $hepsiburadaUpdated = $this->syncProductPagePrices('hepsiburada', $priceParser, $syncedAt, $dryRun);

        $total = $kacmasaUpdated + $trendyolUpdated + $hepsiburadaUpdated;

        $this->info("Kacmasa: {$kacmasaUpdated} | Trendyol: {$trendyolUpdated} | Hepsiburada: {$hepsiburadaUpdated} | Toplam: {$total}");

        Log::info('Marketplace fiyat senkronu tamamlandı', [
            'kacmasa' => $kacmasaUpdated,
            'trendyol' => $trendyolUpdated,
            'hepsiburada' => $hepsiburadaUpdated,
            'total' => $total,
            'dry_run' => $dryRun,
            'synced_at' => $syncedAt->format('c'),
        ]);

        return self::SUCCESS;
    }

    private function syncKacmasaCatalogPrices(
        KacmasaCatalogParser $parser,
        \DateTimeInterface $syncedAt,
        bool $dryRun,
    ): int {
        $pages = max(1, (int) $this->option('pages'));
        $catalog = [];

        for ($page = 1; $page <= $pages; $page++) {
            $url = $page === 1
                ? 'https://kacmasa.com/magaza/NWELT'
                : 'https://kacmasa.com/magaza/NWELT?page='.$page;

            $this->line("Kacmasa sayfa {$page} indiriliyor…");

            $response = $this->fetch($url);

            if ($response === null) {
                break;
            }

            foreach ($parser->parseListingHtml($response) as $item) {
                $catalog[$item['url']] = $item;
            }
        }

        $updated = 0;

        foreach ($catalog as $item) {
            if ($item['price'] === null) {
                continue;
            }

            $product = $this->findProductForKacmasaItem($item);

            if (! $product) {
                continue;
            }

            $payload = [
                'price' => $item['price'],
                'compare_at_price' => $item['compare_at_price'],
                'price_synced_at' => $syncedAt,
                'prices_synced_at' => $syncedAt,
            ];

            if ($dryRun) {
                $this->line("• Kacmasa {$product->name}: {$item['price']} TL");
            } else {
                $product->update($payload);
            }

            $updated++;
        }

        return $updated;
    }

    /**
     * @param  array{url: string, name: string, price: ?float, compare_at_price: ?float}  $item
     */
    private function findProductForKacmasaItem(array $item): ?Product
    {
        $product = Product::query()
            ->where('seller_url', $item['url'])
            ->orWhere('seller_url', 'like', $item['url'].'%')
            ->first();

        if ($product) {
            return $product;
        }

        $catalogSlug = Str::afterLast($item['url'], '/');

        $product = Product::query()
            ->whereNotNull('seller_url')
            ->where('seller_url', '!=', '')
            ->get()
            ->first(fn (Product $candidate) => $this->kacmasaUrlSlug($candidate->seller_url) === $catalogSlug);

        if ($product) {
            return $product;
        }

        $product = Product::query()->where('name', $item['name'])->first();

        if ($product) {
            return $product;
        }

        $slug = Str::slug(Str::before($item['url'], '?'));
        $slug = Str::afterLast($slug, '/');

        return Product::query()->where('slug', $slug)->first();
    }

    private function syncKacmasaProductPagePrices(
        MarketplacePriceParser $parser,
        \DateTimeInterface $syncedAt,
        bool $dryRun,
    ): int {
        $products = Product::query()
            ->whereNotNull('seller_url')
            ->where('seller_url', '!=', '')
            ->whereNull('price')
            ->get();

        $updated = 0;

        foreach ($products as $product) {
            $url = strtok((string) $product->seller_url, '?') ?: $product->seller_url;

            $this->line("Kacmasa ürün sayfası: {$product->name}");

            $html = $this->fetch($url);

            if ($html === null) {
                $this->warn("  → sayfa alınamadı: {$url}");
                continue;
            }

            $price = $parser->parseKacmasaHtml($html);

            if ($price === null) {
                $this->warn('  → fiyat bulunamadı');
                continue;
            }

            if ($dryRun) {
                $this->line("  → {$price} TL");
            } else {
                $product->update([
                    'price' => $price,
                    'price_synced_at' => $syncedAt,
                    'prices_synced_at' => $syncedAt,
                ]);
            }

            $updated++;
        }

        return $updated;
    }

    private function kacmasaUrlSlug(?string $url): ?string
    {
        if (! is_string($url) || $url === '') {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            return null;
        }

        return Str::afterLast(rtrim($path, '/'), '/');
    }

    private function syncProductPagePrices(
        string $marketplace,
        MarketplacePriceParser $parser,
        \DateTimeInterface $syncedAt,
        bool $dryRun,
    ): int {
        $urlColumn = $marketplace.'_url';
        $priceColumn = $marketplace.'_price';

        $products = Product::query()
            ->whereNotNull($urlColumn)
            ->where($urlColumn, '!=', '')
            ->get()
            ->filter(fn (Product $product) => ProductMarketplace::hasProductPageUrl($product, $marketplace));

        $updated = 0;

        foreach ($products as $product) {
            $url = strtok((string) $product->{$urlColumn}, '?') ?: $product->{$urlColumn};

            $this->line(ucfirst($marketplace)." fiyatı: {$product->name}");

            $html = $this->fetch($url);

            if ($html === null) {
                $this->warn("  → sayfa alınamadı: {$url}");
                continue;
            }

            $price = match ($marketplace) {
                'trendyol' => $parser->parseTrendyolHtml($html),
                'hepsiburada' => $parser->parseHepsiburadaHtml($html),
                default => null,
            };

            if ($price === null) {
                $this->warn('  → fiyat bulunamadı');
                continue;
            }

            if ($dryRun) {
                $this->line("  → {$price} TL");
            } else {
                $product->update([
                    $priceColumn => $price,
                    'prices_synced_at' => $syncedAt,
                ]);
            }

            $updated++;
        }

        return $updated;
    }

    private function fetch(string $url): ?string
    {
        $response = Http::withoutVerifying()
            ->timeout(60)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; INWELT-Price-Sync/1.0)',
                'Accept-Language' => 'tr-TR,tr;q=0.9',
            ])
            ->get($url);

        return $response->successful() ? $response->body() : null;
    }
}

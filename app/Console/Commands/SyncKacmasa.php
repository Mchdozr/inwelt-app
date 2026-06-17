<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\KacmasaCatalogParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncKacmasa extends Command
{
    protected $signature = 'inwelt:sync-kacmasa
                            {--pages=3 : Kaç liste sayfası taranacak}
                            {--dry-run : Veritabanına yazmadan önizle}';

    protected $description = 'Kacmasa NWELT mağazasından seller_url senkronu';

    public function handle(KacmasaCatalogParser $parser): int
    {
        $pages = max(1, (int) $this->option('pages'));
        $dryRun = (bool) $this->option('dry-run');
        $catalog = [];

        for ($page = 1; $page <= $pages; $page++) {
            $url = $page === 1
                ? 'https://kacmasa.com/magaza/NWELT'
                : 'https://kacmasa.com/magaza/NWELT?page='.$page;

            $this->line("Sayfa {$page} indiriliyor…");

            $response = Http::withoutVerifying()
                ->timeout(120)
                ->withHeaders(['User-Agent' => 'INWELT-Catalog-Sync/1.0'])
                ->get($url);

            if (! $response->successful()) {
                $this->warn("Sayfa {$page} alınamadı: HTTP {$response->status()}");
                break;
            }

            foreach ($parser->parseListingHtml($response->body()) as $item) {
                $catalog[$item['url']] = $item;
            }
        }

        $this->info(count($catalog).' Kacmasa ürünü bulundu.');

        $updated = 0;
        $unmatched = 0;

        foreach ($catalog as $item) {
            $product = Product::query()
                ->where('seller_url', $item['url'])
                ->orWhere('seller_url', 'like', $item['url'].'%')
                ->first();

            if (! $product) {
                $product = Product::query()
                    ->where('name', $item['name'])
                    ->first();
            }

            if (! $product) {
                $slug = Str::slug(Str::before($item['url'], '?'));
                $slug = Str::afterLast($slug, '/');
                $product = Product::query()->where('slug', $slug)->first();
            }

            if (! $product) {
                $unmatched++;
                continue;
            }

            $payload = [
                'seller_url' => $item['url'],
            ];

            if ($dryRun) {
                $this->line("• {$product->name} → {$item['url']}");
            } else {
                $product->update($payload);
            }

            $updated++;
        }

        $this->info("Güncellenen: {$updated}");
        $this->line("Eşleşmeyen Kacmasa ürünü: {$unmatched}");

        return self::SUCCESS;
    }
}

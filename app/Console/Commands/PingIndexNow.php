<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Product;
use App\Support\IndexNow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingIndexNow extends Command
{
    protected $signature = 'inwelt:ping-indexnow';

    protected $description = 'Yayınlanmış ürün, kategori ve rehber URL’lerini IndexNow ile bildirir';

    public function handle(): int
    {
        if (! IndexNow::key()) {
            $this->warn('INDEXNOW_KEY tanımlı değil.');

            return self::SUCCESS;
        }

        $urls = collect()
            ->merge(Product::where('is_active', true)->get()->map(fn (Product $product) => route('products.show', $product->slug)))
            ->merge(Category::where('is_active', true)->get()->map(fn (Category $category) => route('products.category', $category->slug)))
            ->merge(Guide::published()->get()->map(fn (Guide $guide) => route('guides.show', $guide->slug)))
            ->merge([
                route('home'),
                route('products.index'),
                route('guides.index'),
            ])
            ->unique()
            ->values()
            ->all();

        $chunks = array_chunk($urls, 100);
        $sent = 0;

        foreach ($chunks as $chunk) {
            Http::timeout(15)->put('https://api.indexnow.org/indexnow', IndexNow::payload($chunk));
            $sent += count($chunk);
        }

        $this->info("IndexNow: {$sent} URL bildirildi.");

        return self::SUCCESS;
    }
}

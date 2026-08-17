<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeoOrphanAudit extends Command
{
    protected $signature = 'inwelt:seo-orphan-audit';

    protected $description = 'İç bağlantısı zayıf ürün, kategori ve rehberleri listeler';

    public function handle(): int
    {
        $productUrls = Product::where('is_active', true)->pluck('slug');
        $categorySlugs = Category::where('is_active', true)->pluck('slug');
        $guideSlugs = Guide::published()->pluck('slug');

        $linkedGuideSlugs = Product::query()
            ->whereNotNull('related_guide_slugs')
            ->pluck('related_guide_slugs')
            ->flatten()
            ->filter()
            ->unique();

        $orphanGuides = $guideSlugs->diff($linkedGuideSlugs);

        $this->info('Aktif ürün: '.$productUrls->count());
        $this->info('Aktif kategori: '.$categorySlugs->count());
        $this->info('Yayınlanmış rehber: '.$guideSlugs->count());
        $this->info('Üründen bağlanmayan rehber: '.$orphanGuides->count());

        foreach ($orphanGuides as $slug) {
            $this->line('  - '.$slug);
        }

        $this->line(Str::repeat('-', 40));
        $this->info('Orphan audit tamamlandı.');

        return self::SUCCESS;
    }
}

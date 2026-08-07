<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpsertCatalogProduct extends Command
{
    protected $signature = 'inwelt:upsert-catalog-product
                            {slug : Katalog slug (ör. arac-kettle-termos-500ml)}
                            {--front : Ürünü tüm ürünler listesinde ilk sıraya al}';

    protected $description = 'RebuildCatalog tanımından tek ürün ekler veya günceller (yeni ürün varsayılan ilk sıra)';

    public function handle(RebuildCatalog $rebuildCatalog): int
    {
        $rebuildCatalog->setOutput($this->output);

        return $rebuildCatalog->upsertProductBySlug(
            $this->argument('slug'),
            (bool) $this->option('front'),
        )
            ? self::SUCCESS
            : self::FAILURE;
    }
}

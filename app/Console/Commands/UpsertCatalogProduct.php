<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpsertCatalogProduct extends Command
{
    protected $signature = 'inwelt:upsert-catalog-product {slug : Katalog slug (ör. arac-kettle-termos-500ml)}';

    protected $description = 'RebuildCatalog tanımından tek ürün ekler veya günceller';

    public function handle(RebuildCatalog $rebuildCatalog): int
    {
        $rebuildCatalog->setOutput($this->output);

        return $rebuildCatalog->upsertProductBySlug($this->argument('slug'))
            ? self::SUCCESS
            : self::FAILURE;
    }
}

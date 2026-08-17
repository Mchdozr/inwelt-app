<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('slug');
            $table->string('gtin13', 13)->nullable()->after('sku');
            $table->decimal('rating_value', 2, 1)->nullable()->after('seo_description');
            $table->unsignedInteger('rating_count')->nullable()->after('rating_value');
            $table->string('og_image')->nullable()->after('cover_image');
            $table->json('faq_items')->nullable()->after('rating_count');
            $table->json('related_guide_slugs')->nullable()->after('faq_items');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku',
                'gtin13',
                'rating_value',
                'rating_count',
                'og_image',
                'faq_items',
                'related_guide_slugs',
            ]);
        });
    }
};

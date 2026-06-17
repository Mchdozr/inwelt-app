<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->after('seller_url');
            $table->decimal('compare_at_price', 12, 2)->nullable()->after('price');
            $table->string('currency', 3)->default('TRY')->after('compare_at_price');
            $table->timestamp('price_synced_at')->nullable()->after('currency');
            $table->string('trendyol_url', 500)->nullable()->after('price_synced_at');
            $table->string('hepsiburada_url', 500)->nullable()->after('trendyol_url');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'price',
                'compare_at_price',
                'currency',
                'price_synced_at',
                'trendyol_url',
                'hepsiburada_url',
            ]);
        });
    }
};

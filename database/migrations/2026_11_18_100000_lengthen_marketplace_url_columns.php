<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('seller_url')->nullable()->change();
            $table->text('trendyol_url')->nullable()->change();
            $table->text('hepsiburada_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('seller_url', 500)->nullable()->change();
            $table->string('trendyol_url', 500)->nullable()->change();
            $table->string('hepsiburada_url', 500)->nullable()->change();
        });
    }
};

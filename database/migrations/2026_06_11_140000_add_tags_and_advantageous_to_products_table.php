<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('badge');
            $table->boolean('is_advantageous')->default(false)->after('is_featured');
            $table->index('is_advantageous');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_advantageous']);
            $table->dropColumn(['tags', 'is_advantageous']);
        });
    }
};

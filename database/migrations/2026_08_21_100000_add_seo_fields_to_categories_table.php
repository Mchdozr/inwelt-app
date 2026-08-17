<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('seo_title', 70)->nullable()->after('landing_intro');
            $table->string('seo_description', 160)->nullable()->after('seo_title');
            $table->longText('seo_content')->nullable()->after('seo_description');
            $table->string('hero_image')->nullable()->after('seo_content');
            $table->json('faq_items')->nullable()->after('hero_image');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title',
                'seo_description',
                'seo_content',
                'hero_image',
                'faq_items',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('excerpt', 300);
            $table->longText('body');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cover_image')->nullable();
            $table->string('og_image')->nullable();
            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 160)->nullable();
            $table->unsignedSmallInteger('reading_time_minutes')->nullable();
            $table->json('tags')->nullable();
            $table->json('faq_items')->nullable();
            $table->json('toc')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};

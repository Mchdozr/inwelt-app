<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->text('bio')->nullable()->after('slug');
            $table->string('photo')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('photo');
            $table->json('expertise')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['slug', 'bio', 'photo', 'linkedin_url', 'expertise']);
        });
    }
};

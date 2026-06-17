<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text('landing_intro')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('landing_intro');
        });
    }
};

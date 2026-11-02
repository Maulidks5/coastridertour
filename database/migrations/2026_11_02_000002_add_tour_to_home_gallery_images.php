<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_gallery_images', function (Blueprint $table) {
            $table->foreignId('tour_id')->nullable()->after('alt_text')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('home_gallery_images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tour_id');
        });
    }
};

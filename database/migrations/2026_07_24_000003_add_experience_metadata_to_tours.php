<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('experience_type', 30)->default('zanzibar_tour')->after('tour_category_id');
            $table->unsignedTinyInteger('minimum_age')->nullable()->after('pickup_details');
            $table->string('difficulty', 30)->nullable()->after('minimum_age');
            $table->boolean('is_popular')->default(false)->after('is_featured');

            $table->index(['experience_type', 'is_published', 'sort_order'], 'tours_type_published_sort_index');
            $table->index(['is_popular', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropIndex('tours_type_published_sort_index');
            $table->dropIndex(['is_popular', 'is_published']);
            $table->dropColumn(['experience_type', 'minimum_age', 'difficulty', 'is_popular']);
        });
    }
};

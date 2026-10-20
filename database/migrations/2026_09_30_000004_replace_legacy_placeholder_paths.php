<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        DB::table('tour_categories')->where('image_path', '/images/malta-tour-placeholder.jpg')->update(['image_path' => '/images/coast-rider-placeholder.jpg']);
        DB::table('tours')->where('cover_image_path', '/images/malta-tour-placeholder.jpg')->update(['cover_image_path' => '/images/coast-rider-placeholder.jpg']);
        DB::table('hero_slides')->where('image_path', '/images/malta-tour-placeholder.jpg')->update(['image_path' => '/images/coast-rider-placeholder.jpg']);
    }
    public function down(): void {}
};

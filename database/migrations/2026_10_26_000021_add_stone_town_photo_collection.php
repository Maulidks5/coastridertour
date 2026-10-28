<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/stone-town-tour/cover.webp';

    private const GALLERY = [
        ['/images/tours/stone-town-tour/gallery/waterfront.webp', 'Historic Stone Town buildings beside the Indian Ocean in Zanzibar', 1],
        ['/images/tours/stone-town-tour/gallery/zanzibari-door.webp', 'Traditional carved wooden doorway on a historic Stone Town street', 2],
        ['/images/tours/stone-town-tour/gallery/historic-alley.webp', 'Sunlit narrow alley between historic buildings in Stone Town', 3],
        ['/images/tours/stone-town-tour/gallery/street-life.webp', 'Local residents walking through a bright Stone Town street', 4],
        ['/images/tours/stone-town-tour/gallery/ornate-entrance.webp', 'Weathered carved Zanzibar door showcasing traditional craftsmanship', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => self::COVER,
            'updated_at' => now(),
        ]);

        foreach (self::GALLERY as [$path, $alt, $order]) {
            DB::table('tour_images')->updateOrInsert(
                ['tour_id' => $tour->id, 'path' => $path],
                ['alt_text' => $alt, 'sort_order' => $order, 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/village-tour.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

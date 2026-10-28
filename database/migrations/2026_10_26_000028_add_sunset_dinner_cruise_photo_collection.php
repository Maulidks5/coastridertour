<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/private-sunset-dinner-cruise/cover.webp';

    private const GALLERY = [
        ['/images/tours/private-sunset-dinner-cruise/gallery/dhow-sunset.webp', 'Traditional wooden boat resting off a Zanzibar beach at sunset', 1],
        ['/images/tours/private-sunset-dinner-cruise/gallery/ocean-horizon.webp', 'Boats silhouetted across Zanzibar’s golden sunset horizon', 2],
        ['/images/tours/private-sunset-dinner-cruise/gallery/sunset-sailing.webp', 'Traditional dhow sailing across the ocean near Zanzibar at sunset', 3],
        ['/images/tours/private-sunset-dinner-cruise/gallery/seaside-table.webp', 'Elegant private dinner table prepared beside the ocean at sunset', 4],
        ['/images/tours/private-sunset-dinner-cruise/gallery/seafood-dinner.webp', 'Candlelit seafood dinner shared around an intimate table', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'private-sunset-dinner-cruise')->first();
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
        $tour = DB::table('tours')->where('slug', 'private-sunset-dinner-cruise')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/hero-zanzibar-dhow.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

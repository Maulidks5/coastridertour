<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/safari-blue/cover.webp';

    private const GALLERY = [
        ['/images/tours/safari-blue/gallery/dhow-red-sail.webp', 'Traditional Zanzibar dhow resting on white sand', 1],
        ['/images/tours/safari-blue/gallery/dhow-beach.webp', 'Traditional dhow beside a quiet Zanzibar sandbank', 2],
        ['/images/tours/safari-blue/gallery/aerial-shore.webp', 'Aerial view of a dhow and clear turquoise Zanzibar water', 3],
        ['/images/tours/safari-blue/gallery/snorkeling.webp', 'Snorkeling in the clear shallow waters of Zanzibar', 4],
        ['/images/tours/safari-blue/gallery/seafood.webp', 'Zanzibar seafood lunch served during an island experience', 5],
        ['/images/tours/safari-blue/gallery/turquoise-beach.webp', 'Turquoise Indian Ocean water along the Zanzibar coast', 6],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'safari-blue')->first();
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
        $tour = DB::table('tours')->where('slug', 'safari-blue')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/safari-blue.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

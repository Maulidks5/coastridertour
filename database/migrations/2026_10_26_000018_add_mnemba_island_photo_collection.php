<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/mnemba-island/cover.webp';

    private const GALLERY = [
        ['/images/tours/mnemba-island/gallery/dolphins-surface.webp', 'Dolphins swimming in the clear waters off Zanzibar', 1],
        ['/images/tours/mnemba-island/gallery/dolphins-underwater.webp', 'A pod of dolphins swimming underwater near Zanzibar', 2],
        ['/images/tours/mnemba-island/gallery/snorkeling-reef.webp', 'Snorkeller exploring a colourful tropical coral reef', 3],
        ['/images/tours/mnemba-island/gallery/boat-trip.webp', 'Traditional Zanzibar boats ready for an Indian Ocean excursion', 4],
        ['/images/tours/mnemba-island/gallery/island-beach.webp', 'White-sand beach and turquoise water on the Zanzibar coast', 5],
        ['/images/tours/mnemba-island/gallery/zanzibar-boats.webp', 'Traditional boats sailing across the ocean in Zanzibar', 6],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'mnemba-island')->first();
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
        $tour = DB::table('tours')->where('slug', 'mnemba-island')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/mnemba-island.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/design-your-day-private-tour/cover.webp';

    private const GALLERY = [
        ['/images/tours/design-your-day-private-tour/gallery/stone-town.webp', 'Aerial view of historic Stone Town for a custom cultural itinerary', 1],
        ['/images/tours/design-your-day-private-tour/gallery/spice-farm.webp', 'Tropical fruits representing a Zanzibar spice farm experience', 2],
        ['/images/tours/design-your-day-private-tour/gallery/jozani-forest.webp', 'Zanzibar red colobus monkey for a Jozani Forest itinerary', 3],
        ['/images/tours/design-your-day-private-tour/gallery/ocean-adventure.webp', 'Traditional boat and clear water for a private ocean experience', 4],
        ['/images/tours/design-your-day-private-tour/gallery/sunset-dhow.webp', 'Traditional dhow at sunset for a personalised evening experience', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'design-your-day-private-tour')->first();
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
        $tour = DB::table('tours')->where('slug', 'design-your-day-private-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/hero-zanzibar.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

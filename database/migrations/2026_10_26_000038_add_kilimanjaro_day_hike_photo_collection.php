<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/kilimanjaro-day-hike/cover.webp';

    private const GALLERY = [
        ['/images/tours/kilimanjaro-day-hike/gallery/summit-trail.webp', 'Hikers crossing a snowy high-altitude trail on Mount Kilimanjaro', 1],
        ['/images/tours/kilimanjaro-day-hike/gallery/snowy-sunrise.webp', 'Trekker standing in the snow at sunrise on Mount Kilimanjaro', 2],
        ['/images/tours/kilimanjaro-day-hike/gallery/high-altitude-trail.webp', 'Mountain trail winding through Kilimanjaro’s alpine landscape', 3],
        ['/images/tours/kilimanjaro-day-hike/gallery/kilimanjaro-hikers.webp', 'Hikers walking together on a Kilimanjaro trail', 4],
        ['/images/tours/kilimanjaro-day-hike/gallery/rainforest-stream.webp', 'Rainforest and stream on the lower slopes of Mount Kilimanjaro', 5],
    ];

    public function up(): void
    {
        DB::table('tour_categories')->where('slug', 'kilimanjaro-treks')->update([
            'image_path' => self::COVER,
            'updated_at' => now(),
        ]);

        $tour = DB::table('tours')->where('slug', 'kilimanjaro-day-hike')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => self::COVER,
            'updated_at' => now(),
        ]);

        DB::table('tour_images')->where('tour_id', $tour->id)->delete();
        foreach (self::GALLERY as [$path, $alt, $order]) {
            DB::table('tour_images')->insert([
                'tour_id' => $tour->id,
                'path' => $path,
                'alt_text' => $alt,
                'sort_order' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('tour_categories')->where('slug', 'kilimanjaro-treks')->update([
            'image_path' => '/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp',
            'updated_at' => now(),
        ]);

        $tour = DB::table('tours')->where('slug', 'kilimanjaro-day-hike')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp',
            'updated_at' => now(),
        ]);

        DB::table('tour_images')->where('tour_id', $tour->id)->delete();
    }
};

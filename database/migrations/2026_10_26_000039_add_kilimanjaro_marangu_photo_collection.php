<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/kilimanjaro-marangu-route/cover.webp';

    private const GALLERY = [
        ['/images/tours/kilimanjaro-marangu-route/gallery/mawenzi-sunrise.webp', 'Sunrise above Mawenzi during a Mount Kilimanjaro trek', 1],
        ['/images/tours/kilimanjaro-marangu-route/gallery/marangu-trekker.webp', 'Trekker walking toward Mount Kilimanjaro on the Marangu journey', 2],
        ['/images/tours/kilimanjaro-marangu-route/gallery/rocky-alpine-zone.webp', 'Rocky alpine landscape high on Mount Kilimanjaro', 3],
        ['/images/tours/kilimanjaro-marangu-route/gallery/kilimanjaro-ice-cap.webp', 'Kilimanjaro ice cap near the summit zone', 4],
        ['/images/tours/kilimanjaro-marangu-route/gallery/mountain-rest-stop.webp', 'Trekker resting during a guided Kilimanjaro climb', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-marangu-route')->first();
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
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-marangu-route')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp',
            'updated_at' => now(),
        ]);

        DB::table('tour_images')->where('tour_id', $tour->id)->delete();
    }
};

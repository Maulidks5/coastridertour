<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/mikumi-day-safari/cover.webp';

    private const GALLERY = [
        ['/images/tours/mikumi-day-safari/gallery/mikumi-zebra.webp', 'Zebra grazing in Mikumi National Park', 1],
        ['/images/tours/mikumi-day-safari/gallery/baby-elephant.webp', 'Young elephant among tall grass in Tanzania', 2],
        ['/images/tours/mikumi-day-safari/gallery/giraffes.webp', 'Giraffes crossing the Tanzanian savanna', 3],
        ['/images/tours/mikumi-day-safari/gallery/lion.webp', 'Lion seen during a Tanzania wildlife safari', 4],
        ['/images/tours/mikumi-day-safari/gallery/mikumi-landscape.webp', 'Open wildlife landscape in Mikumi National Park', 5],
    ];

    private const PREVIOUS_GALLERY = [
        ['/images/experiences/safari-mikumi-2-day.webp', 'Mikumi Day Safari safari experience', 1],
        ['/images/experiences/safari-tarangire.webp', 'Mikumi Day Safari safari experience', 2],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'mikumi-day-safari')->first();
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
        $tour = DB::table('tours')->where('slug', 'mikumi-day-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/safari-mikumi-day.webp',
            'updated_at' => now(),
        ]);

        DB::table('tour_images')->where('tour_id', $tour->id)->delete();
        foreach (self::PREVIOUS_GALLERY as [$path, $alt, $order]) {
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
};

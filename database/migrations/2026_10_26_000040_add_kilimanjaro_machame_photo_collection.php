<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/kilimanjaro-machame-route/cover.webp';

    private const GALLERY = [
        ['/images/tours/kilimanjaro-machame-route/gallery/high-altitude-camp.webp', 'Tented high-altitude camp during a Kilimanjaro expedition', 1],
        ['/images/tours/kilimanjaro-machame-route/gallery/alpine-rest.webp', 'Mountain crew member resting in Kilimanjaro’s alpine zone', 2],
        ['/images/tours/kilimanjaro-machame-route/gallery/machame-hiking-team.webp', 'Guided hiking team crossing a rugged Kilimanjaro landscape', 3],
        ['/images/tours/kilimanjaro-machame-route/gallery/summit-sunrise.webp', 'Climber at the snowy Kilimanjaro summit near sunrise', 4],
        ['/images/tours/kilimanjaro-machame-route/gallery/scenic-mountain-trail.webp', 'Hikers ascending a scenic Mount Kilimanjaro trail', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-machame-route')->first();
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
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-machame-route')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp',
            'updated_at' => now(),
        ]);

        DB::table('tour_images')->where('tour_id', $tour->id)->delete();
    }
};

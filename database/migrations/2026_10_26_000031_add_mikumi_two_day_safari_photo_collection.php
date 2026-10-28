<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/mikumi-two-day-safari/cover.webp';

    private const GALLERY = [
        ['/images/tours/mikumi-two-day-safari/gallery/mikumi-giraffe.webp', 'Giraffe standing in Mikumi National Park', 1],
        ['/images/tours/mikumi-two-day-safari/gallery/mikumi-zebras.webp', 'Zebra herd grazing in Mikumi National Park', 2],
        ['/images/tours/mikumi-two-day-safari/gallery/game-drive.webp', 'Safari vehicles travelling on a Tanzanian dirt road', 3],
        ['/images/tours/mikumi-two-day-safari/gallery/safari-guests.webp', 'Travellers enjoying a guided safari in Tanzania', 4],
        ['/images/tours/mikumi-two-day-safari/gallery/safari-lodge.webp', 'Safari lodge surrounded by vegetation in Tanzania', 5],
    ];

    private const PREVIOUS_GALLERY = [
        ['/images/experiences/safari-mikumi-day.webp', 'Mikumi Safari safari experience', 1],
        ['/images/experiences/safari-ngorongoro.webp', 'Mikumi Safari safari experience', 2],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'mikumi-two-day-safari')->first();
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
        $tour = DB::table('tours')->where('slug', 'mikumi-two-day-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/safari-mikumi-2-day.webp',
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

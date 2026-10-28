<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/northern-circuit-safari/cover.webp';

    private const GALLERY = [
        ['/images/tours/northern-circuit-safari/gallery/tarangire-elephant.webp', 'African elephant in Tarangire National Park', 1],
        ['/images/tours/northern-circuit-safari/gallery/manyara-zebras.webp', 'Zebra herd beside Lake Manyara with flamingos beyond', 2],
        ['/images/tours/northern-circuit-safari/gallery/manyara-flamingos.webp', 'Flamingos wading in a Tanzanian lake', 3],
        ['/images/tours/northern-circuit-safari/gallery/ngorongoro-zebras.webp', 'Zebras feeding inside Ngorongoro Crater', 4],
        ['/images/tours/northern-circuit-safari/gallery/serengeti-lion-family.webp', 'Lioness and cub resting in Serengeti grassland', 5],
    ];

    private const PREVIOUS_GALLERY = [
        ['/images/experiences/tanzania-safaris.webp', 'Northern Circuit safari experience', 1],
        ['/images/experiences/safari-tarangire.webp', 'Northern Circuit safari experience', 2],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'northern-circuit-safari')->first();
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
        $tour = DB::table('tours')->where('slug', 'northern-circuit-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/safari-ngorongoro.webp',
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

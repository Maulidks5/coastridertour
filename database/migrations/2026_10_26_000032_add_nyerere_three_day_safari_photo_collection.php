<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/nyerere-three-day-safari/cover.webp';

    private const GALLERY = [
        ['/images/tours/nyerere-three-day-safari/gallery/hippos.webp', 'Mother hippopotamus and calf in Tanzanian waters', 1],
        ['/images/tours/nyerere-three-day-safari/gallery/river-elephants.webp', 'African elephants playing in a river', 2],
        ['/images/tours/nyerere-three-day-safari/gallery/lion-sighting.webp', 'Guests photographing a lion during a safari', 3],
        ['/images/tours/nyerere-three-day-safari/gallery/giraffe-game-drive.webp', 'Giraffes crossing in front of a safari vehicle', 4],
        ['/images/tours/nyerere-three-day-safari/gallery/lion-cubs.webp', 'Lion cubs resting in golden Tanzanian grass', 5],
    ];

    private const PREVIOUS_GALLERY = [
        ['/images/experiences/safari-tarangire.webp', 'Nyerere Safari safari experience', 1],
        ['/images/experiences/tanzania-safaris.webp', 'Nyerere Safari safari experience', 2],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'nyerere-three-day-safari')->first();
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
        $tour = DB::table('tours')->where('slug', 'nyerere-three-day-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/safari-nyerere.webp',
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

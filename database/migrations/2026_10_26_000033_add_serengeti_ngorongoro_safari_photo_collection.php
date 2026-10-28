<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/serengeti-ngorongoro-safari/cover.webp';

    private const GALLERY = [
        ['/images/tours/serengeti-ngorongoro-safari/gallery/ngorongoro-crater.webp', 'Panoramic view across Ngorongoro Crater in Tanzania', 1],
        ['/images/tours/serengeti-ngorongoro-safari/gallery/crater-game-drive.webp', 'Safari vehicles on a dusty road inside Ngorongoro Crater', 2],
        ['/images/tours/serengeti-ngorongoro-safari/gallery/serengeti-lion.webp', 'Male lion resting in the Serengeti savanna', 3],
        ['/images/tours/serengeti-ngorongoro-safari/gallery/serengeti-elephants.webp', 'African elephants grazing across the Serengeti plains', 4],
        ['/images/tours/serengeti-ngorongoro-safari/gallery/serengeti-cheetah.webp', 'Cheetah watching from golden Serengeti grassland', 5],
    ];

    private const PREVIOUS_GALLERY = [
        ['/images/experiences/safari-ngorongoro.webp', 'Serengeti & Ngorongoro safari experience', 1],
        ['/images/experiences/safari-tarangire.webp', 'Serengeti & Ngorongoro safari experience', 2],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'serengeti-ngorongoro-safari')->first();
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
        $tour = DB::table('tours')->where('slug', 'serengeti-ngorongoro-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/tanzania-safaris.webp',
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

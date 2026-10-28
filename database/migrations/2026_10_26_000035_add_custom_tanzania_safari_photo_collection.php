<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/custom-tanzania-safari/cover.webp';

    private const GALLERY = [
        ['/images/tours/custom-tanzania-safari/gallery/private-game-drive.webp', 'Private safari vehicles overlooking Ngorongoro Crater', 1],
        ['/images/tours/custom-tanzania-safari/gallery/safari-lodge.webp', 'Traveller relaxing at a safari lodge in Arusha', 2],
        ['/images/tours/custom-tanzania-safari/gallery/tanzania-leopard.webp', 'Leopard resting on a tree during a Tanzania safari', 3],
        ['/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp', 'African elephant with Mount Kilimanjaro in the distance', 4],
        ['/images/tours/custom-tanzania-safari/gallery/ngorongoro-hyenas.webp', 'Spotted hyenas in Ngorongoro Crater', 5],
    ];

    private const PREVIOUS_GALLERY = [
        ['/images/experiences/tanzania-safaris.webp', 'Custom Tanzania Safari safari experience', 1],
        ['/images/experiences/safari-ngorongoro.webp', 'Custom Tanzania Safari safari experience', 2],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'custom-tanzania-safari')->first();
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
        $tour = DB::table('tours')->where('slug', 'custom-tanzania-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/safari-tarangire.webp',
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

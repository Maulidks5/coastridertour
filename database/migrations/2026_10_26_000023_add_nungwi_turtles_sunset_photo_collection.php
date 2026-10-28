<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/nungwi-turtles-sunset/cover.webp';

    private const GALLERY = [
        ['/images/tours/nungwi-turtles-sunset/gallery/nungwi-turtle.webp', 'Sea turtles swimming in clear water at Nungwi, Zanzibar', 1],
        ['/images/tours/nungwi-turtles-sunset/gallery/sea-turtle.webp', 'Sea turtle gliding through clear tropical water', 2],
        ['/images/tours/nungwi-turtles-sunset/gallery/nungwi-beach.webp', 'White sand and turquoise water along Nungwi Beach', 3],
        ['/images/tours/nungwi-turtles-sunset/gallery/dhow-beach.webp', 'Traditional wooden boat resting off a Zanzibar beach', 4],
        ['/images/tours/nungwi-turtles-sunset/gallery/sunset-shore.webp', 'Golden sunset and boats along Zanzibar’s northern coast', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'nungwi-turtles-sunset')->first();
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
        $tour = DB::table('tours')->where('slug', 'nungwi-turtles-sunset')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/nungwi-aquarium.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/stone-town-prison-island/cover.webp';

    private const GALLERY = [
        ['/images/tours/stone-town-prison-island/gallery/historic-alley.webp', 'Sunlit historic alley during a guided Stone Town walk', 1],
        ['/images/tours/stone-town-prison-island/gallery/zanzibari-door.webp', 'Traditional carved Zanzibari doorway in Stone Town', 2],
        ['/images/tours/stone-town-prison-island/gallery/boat-crossing.webp', 'Traditional sailing boat crossing Zanzibar’s turquoise coastal water', 3],
        ['/images/tours/stone-town-prison-island/gallery/giant-tortoise.webp', 'Close view of an Aldabra giant tortoise on Prison Island', 4],
        ['/images/tours/stone-town-prison-island/gallery/tortoise-sanctuary.webp', 'Aldabra giant tortoise in the island sanctuary', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-prison-island')->first();
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
        $tour = DB::table('tours')->where('slug', 'stone-town-prison-island')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/prison-island.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

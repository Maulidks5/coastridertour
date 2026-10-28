<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/jozani-forest/cover.webp';

    private const GALLERY = [
        ['/images/tours/jozani-forest/gallery/red-colobus-closeup.webp', 'Close portrait of an endemic Zanzibar red colobus monkey in Jozani Forest', 1],
        ['/images/tours/jozani-forest/gallery/red-colobus-branch.webp', 'Zanzibar red colobus monkey resting on a branch in Jozani Forest', 2],
        ['/images/tours/jozani-forest/gallery/red-colobus-forest.webp', 'Red colobus monkey surrounded by the green canopy of a Zanzibar forest', 3],
        ['/images/tours/jozani-forest/gallery/blue-monkey.webp', 'Blue monkey sitting among the trees in a Zanzibar tropical forest', 4],
        ['/images/tours/jozani-forest/gallery/tropical-foliage.webp', 'Lush tropical foliage growing in Zanzibar', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'jozani-forest')->first();
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
        $tour = DB::table('tours')->where('slug', 'jozani-forest')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/jozani-forest.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/prison-island-nakupenda/cover.webp';

    private const GALLERY = [
        ['/images/tours/prison-island-nakupenda/gallery/giant-tortoise-portrait.webp', 'Close portrait of an Aldabra giant tortoise in Zanzibar', 1],
        ['/images/tours/prison-island-nakupenda/gallery/giant-tortoise-sanctuary.webp', 'Aldabra giant tortoise resting in its Zanzibar sanctuary', 2],
        ['/images/tours/prison-island-nakupenda/gallery/sandbank.webp', 'White-sand tropical sandbank surrounded by clear ocean water', 3],
        ['/images/tours/prison-island-nakupenda/gallery/dhow-cruise.webp', 'Traditional sailing boat crossing turquoise tropical water', 4],
        ['/images/tours/prison-island-nakupenda/gallery/seafood-lunch.webp', 'Fresh Zanzibar seafood lunch served during an island excursion', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'prison-island-nakupenda')->first();
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
        $tour = DB::table('tours')->where('slug', 'prison-island-nakupenda')->first();
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

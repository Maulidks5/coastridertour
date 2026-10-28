<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/stone-town-spice-tour/cover.webp';

    private const GALLERY = [
        ['/images/tours/stone-town-spice-tour/gallery/breadfruit.webp', 'Breadfruit growing beneath tropical leaves at a Zanzibar spice farm', 1],
        ['/images/tours/stone-town-spice-tour/gallery/cloves.webp', 'Aromatic dried cloves introduced during the spice farm visit', 2],
        ['/images/tours/stone-town-spice-tour/gallery/stone-town-waterfront.webp', 'Historic Stone Town waterfront beside the Indian Ocean', 3],
        ['/images/tours/stone-town-spice-tour/gallery/historic-alley.webp', 'Sunlit narrow alley during a guided Stone Town walk', 4],
        ['/images/tours/stone-town-spice-tour/gallery/street-life.webp', 'Everyday street life among Stone Town’s historic buildings', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-spice-tour')->first();
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
        $tour = DB::table('tours')->where('slug', 'stone-town-spice-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/experiences/spice-tour.webp',
            'updated_at' => now(),
        ]);
        DB::table('tour_images')
            ->where('tour_id', $tour->id)
            ->whereIn('path', array_column(self::GALLERY, 0))
            ->delete();
    }
};

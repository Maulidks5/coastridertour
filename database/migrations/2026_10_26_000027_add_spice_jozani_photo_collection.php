<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/spice-jozani-forest/cover.webp';

    private const GALLERY = [
        ['/images/tours/spice-jozani-forest/gallery/breadfruit.webp', 'Breadfruit growing beneath tropical leaves at a Zanzibar spice farm', 1],
        ['/images/tours/spice-jozani-forest/gallery/cloves.webp', 'Aromatic dried cloves introduced during the spice farm visit', 2],
        ['/images/tours/spice-jozani-forest/gallery/tropical-fruit.webp', 'Fresh pineapples and mangoes ready for a tropical fruit tasting', 3],
        ['/images/tours/spice-jozani-forest/gallery/red-colobus-forest.webp', 'Zanzibar red colobus monkey among the trees in Jozani Forest', 4],
        ['/images/tours/spice-jozani-forest/gallery/red-colobus-branch.webp', 'Red colobus monkey resting on a forest branch', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'spice-jozani-forest')->first();
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
        $tour = DB::table('tours')->where('slug', 'spice-jozani-forest')->first();
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

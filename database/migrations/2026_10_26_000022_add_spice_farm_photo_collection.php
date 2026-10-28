<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/spice-tour/cover.webp';

    private const GALLERY = [
        ['/images/tours/spice-tour/gallery/breadfruit.webp', 'Breadfruit growing beneath green tropical leaves in Zanzibar', 1],
        ['/images/tours/spice-tour/gallery/cloves.webp', 'Close-up view of aromatic dried cloves', 2],
        ['/images/tours/spice-tour/gallery/spices.webp', 'Cinnamon, cloves, nutmeg and other whole spices', 3],
        ['/images/tours/spice-tour/gallery/fruit-stall.webp', 'Fresh pineapples and mangoes at a Zanzibar fruit stall', 4],
        ['/images/tours/spice-tour/gallery/jackfruit.webp', 'Jackfruit growing naturally on a tropical tree', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'spice-tour')->first();
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
        $tour = DB::table('tours')->where('slug', 'spice-tour')->first();
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

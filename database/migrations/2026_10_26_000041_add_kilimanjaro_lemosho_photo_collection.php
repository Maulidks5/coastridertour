<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COVER = '/images/tours/kilimanjaro-lemosho-route/cover.webp';

    private const GALLERY = [
        ['/images/tours/kilimanjaro-lemosho-route/gallery/western-rainforest.webp', 'Lush rainforest scenery on Kilimanjaro’s lower western slopes', 1],
        ['/images/tours/kilimanjaro-lemosho-route/gallery/shira-plateau-trail.webp', 'Wide high-altitude trail across Kilimanjaro’s plateau landscape', 2],
        ['/images/tours/kilimanjaro-lemosho-route/gallery/lemosho-tented-camp.webp', 'Tented mountain camp during a Kilimanjaro expedition', 3],
        ['/images/tours/kilimanjaro-lemosho-route/gallery/mountain-guides.webp', 'Two trekkers prepared for cold conditions on Mount Kilimanjaro', 4],
        ['/images/tours/kilimanjaro-lemosho-route/gallery/summit-team.webp', 'Climbing team crossing the snowy summit zone of Kilimanjaro', 5],
    ];

    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-lemosho-route')->first();
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
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-lemosho-route')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'cover_image_path' => '/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp',
            'updated_at' => now(),
        ]);

        DB::table('tour_images')->where('tour_id', $tour->id)->delete();
    }
};

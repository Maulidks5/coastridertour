<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $galleries = [
        'mikumi-day-safari' => ['/images/experiences/safari-mikumi-2-day.webp', '/images/experiences/safari-tarangire.webp'],
        'mikumi-two-day-safari' => ['/images/experiences/safari-mikumi-day.webp', '/images/experiences/safari-ngorongoro.webp'],
        'nyerere-three-day-safari' => ['/images/experiences/safari-tarangire.webp', '/images/experiences/tanzania-safaris.webp'],
        'serengeti-ngorongoro-safari' => ['/images/experiences/safari-ngorongoro.webp', '/images/experiences/safari-tarangire.webp'],
        'northern-circuit-safari' => ['/images/experiences/tanzania-safaris.webp', '/images/experiences/safari-tarangire.webp'],
        'custom-tanzania-safari' => ['/images/experiences/tanzania-safaris.webp', '/images/experiences/safari-ngorongoro.webp'],
    ];

    public function up(): void
    {
        foreach ($this->galleries as $slug => $paths) {
            $tour = DB::table('tours')->where('slug', $slug)->first(['id', 'title']);
            if (! $tour) continue;
            foreach ($paths as $position => $path) {
                if (DB::table('tour_images')->where('tour_id', $tour->id)->where('path', $path)->exists()) continue;
                DB::table('tour_images')->insert([
                    'tour_id' => $tour->id,
                    'path' => $path,
                    'alt_text' => $tour->title.' safari experience',
                    'sort_order' => $position + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->galleries as $slug => $paths) {
            $tourId = DB::table('tours')->where('slug', $slug)->value('id');
            if ($tourId) DB::table('tour_images')->where('tour_id', $tourId)->whereIn('path', $paths)->delete();
        }
    }
};

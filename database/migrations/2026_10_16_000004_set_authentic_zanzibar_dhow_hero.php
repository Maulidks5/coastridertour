<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $heroId = DB::table('hero_slides')->orderBy('sort_order')->value('id');

        if ($heroId) {
            DB::table('hero_slides')->where('id', $heroId)->update([
                'image_path' => '/images/experiences/hero-zanzibar-dhow.webp',
                'image_alt' => 'Traditional dhow sailing at sunset in Zanzibar',
                'image_position' => 'center',
            ]);
        }
    }

    public function down(): void
    {
        $heroId = DB::table('hero_slides')->orderBy('sort_order')->value('id');

        if ($heroId) {
            DB::table('hero_slides')->where('id', $heroId)->update([
                'image_path' => '/images/experiences/hero-zanzibar-premium.jpg',
                'image_alt' => 'Zanzibar beach and Indian Ocean',
                'image_position' => 'center',
            ]);
        }
    }
};

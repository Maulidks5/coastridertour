<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $base = '/images/experiences/';

        $categories = [
            'island-ocean-tours' => 'safari-blue.webp',
            'nature-wildlife' => 'jozani-forest.webp',
            'culture-local-life' => 'village-tour.webp',
            'beaches-coastal' => 'kendwa-beach.webp',
            'water-sports' => 'kitesurfing.webp',
            'tanzania-safaris' => 'hero-safari.webp',
        ];
        foreach ($categories as $slug => $file) {
            DB::table('tour_categories')->where('slug', $slug)->update(['image_path' => $base.$file]);
        }

        $tours = [
            'prison-island', 'safari-blue', 'mnemba-island', 'fishing-game-tour',
            'snapper-fishing', 'jozani-forest', 'nungwi-aquarium', 'salaam-cave',
            'mangapwani-coral-cave', 'butterfly-tour', 'spice-tour', 'village-tour',
            'swahili-cooking-class', 'kendwa-beach', 'the-rock-restaurant', 'glass-kayaking',
            'stand-up-paddleboarding', 'kitesurfing', 'spearfishing', 'jet-ski-adventure',
            'water-sports-tour', 'tanzania-safaris',
        ];
        foreach ($tours as $slug) {
            DB::table('tours')->where('slug', $slug)->update(['cover_image_path' => $base.$slug.'.webp']);
        }

        $heroes = ['hero-zanzibar-premium.jpg', 'hero-ocean.webp', 'hero-safari.webp'];
        foreach (DB::table('hero_slides')->orderBy('sort_order')->pluck('id') as $index => $id) {
            if (isset($heroes[$index])) {
                DB::table('hero_slides')->where('id', $id)->update(['image_path' => $base.$heroes[$index]]);
            }
        }

        DB::table('site_settings')->where('key', 'hero_image_path')->update(['value' => $base.'hero-zanzibar-premium.jpg']);
        DB::table('site_settings')->where('key', 'cta_image_path')->update(['value' => $base.'cta-zanzibar.webp']);
    }

    public function down(): void
    {
        // Keep valid image assignments when rolling back; no user uploads are overwritten.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['homepage_video_enabled', 'Show homepage video', 'boolean', '1'],
            ['homepage_video_path', 'Homepage video', 'video', '/videos/ngorongoro-elephants-hero.webm'],
            ['homepage_video_poster_path', 'Homepage video poster', 'image', '/images/experiences/hero-safari.webp'],
            ['homepage_video_title', 'Homepage video title', 'text', 'A glimpse of the wild, before you arrive'],
            ['homepage_video_description', 'Homepage video description', 'textarea', 'From Zanzibar\'s coast to Tanzania\'s iconic parks, every journey is planned with local care and private comfort.'],
            ['homepage_video_primary_label', 'Homepage video primary button', 'text', 'Explore Tanzania safaris'],
            ['homepage_video_primary_url', 'Homepage video primary button link', 'text', '/safaris'],
            ['homepage_video_secondary_label', 'Homepage video secondary button', 'text', 'Explore Zanzibar tours'],
            ['homepage_video_secondary_url', 'Homepage video secondary button link', 'text', '/tours'],
        ];

        foreach ($settings as $order => [$key, $label, $type, $value]) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                ['group' => 'media', 'label' => $label, 'type' => $type, 'value' => $value, 'sort_order' => 20 + $order, 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'homepage_video_enabled', 'homepage_video_path', 'homepage_video_poster_path',
            'homepage_video_title', 'homepage_video_description', 'homepage_video_primary_label',
            'homepage_video_primary_url', 'homepage_video_secondary_label', 'homepage_video_secondary_url',
        ])->delete();
    }
};

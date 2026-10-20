<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $links = [
            ['key' => 'facebook_url', 'label' => 'Facebook URL', 'value' => 'https://www.facebook.com/share/1EnsuYC6DD/', 'sort_order' => 0],
            ['key' => 'instagram_url', 'label' => 'Instagram URL', 'value' => 'https://www.instagram.com/coastridertour/', 'sort_order' => 1],
            ['key' => 'tiktok_url', 'label' => 'TikTok URL', 'value' => 'https://www.tiktok.com/@coastridertour', 'sort_order' => 2],
        ];

        foreach ($links as $link) {
            DB::table('site_settings')->updateOrInsert(['key' => $link['key']], $link + [
                'group' => 'social', 'type' => 'url', 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        Cache::forget('site-settings.public');
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'tiktok_url')->delete();
        Cache::forget('site-settings.public');
    }
};

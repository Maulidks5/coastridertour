<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')->where('key', 'why_title')->update(['value' => 'Local expertise, reliable service']);
        DB::table('home_content_items')->where('section', 'why')->where('title', 'Experienced Local Guides')->update(['description' => 'Friendly local guides who know these islands.']);
        DB::table('home_content_items')->where('section', 'why')->where('title', 'Competitive Prices')->update(['description' => 'Clear local pricing confirmed before your trip.']);
        DB::table('home_content_items')->where('section', 'why')->where('title', 'Safe Journeys')->update(['description' => 'Safety details are confirmed for each selected experience.']);
        $first = DB::table('hero_slides')->where('is_active', true)->orderBy('sort_order')->value('id');
        if ($first) DB::table('hero_slides')->where('id', '!=', $first)->update(['is_active' => false]);
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'why_title')->update(['value' => 'Trusted by travellers worldwide']);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')->where('key', 'phone')->update([
            'value' => '+255 671 921 474',
            'updated_at' => now(),
        ]);
        DB::table('site_settings')->where('key', 'whatsapp_number')->update([
            'value' => '255671921474',
            'updated_at' => now(),
        ]);
        Cache::forget('site-settings.public');
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'phone')->update([
            'value' => '+255 773 429 169',
            'updated_at' => now(),
        ]);
        DB::table('site_settings')->where('key', 'whatsapp_number')->update([
            'value' => '255773429169',
            'updated_at' => now(),
        ]);
        Cache::forget('site-settings.public');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const PRICES = [
        'prison-island' => 50,
        'nakupenda-beach' => 60,
        'safari-blue' => 85,
        'mnemba-island' => 50,
        'fishing-game-tour' => 0,
        'snapper-fishing' => 0,
        'jozani-forest' => 40,
        'nungwi-aquarium' => 45,
        'salaam-cave' => 30,
        'mangapwani-coral-cave' => 30,
        'butterfly-tour' => 30,
        'spice-tour' => 25,
        'village-tour' => 35,
        'swahili-cooking-class' => 55,
        'kendwa-beach' => 35,
        'the-rock-restaurant' => 35,
        'glass-kayaking' => 35,
        'stand-up-paddleboarding' => 25,
        'kitesurfing' => 70,
        'spearfishing' => 0,
        'jet-ski-adventure' => 60,
        'water-sports-tour' => 100,
        'tanzania-safaris' => 0,
    ];

    public function up(): void
    {
        foreach (self::PRICES as $slug => $price) {
            DB::table('tours')->where('slug', $slug)->update([
                'price' => $price,
                'currency' => 'USD',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('tours')->whereIn('slug', array_keys(self::PRICES))->update([
            'price' => 0,
            'updated_at' => now(),
        ]);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('transport_routes')
            ->where('pickup_location', 'Zanzibar International Airport (ZNZ)')
            ->where('destination', 'Stone Town')
            ->update(['van_price' => 45, 'updated_at' => now()]);

        DB::table('transport_routes')
            ->where('pickup_location', 'Zanzibar International Airport (ZNZ)')
            ->whereIn('destination', [
                'Paje / Jambiani',
                'Nungwi / Kendwa',
                'Matemwe / Kiwengwa',
                'Michamvi',
                'Kizimkazi / Makunduchi',
            ])
            ->update(['van_price' => 80, 'updated_at' => now()]);
    }

    public function down(): void
    {
        $prices = [
            'Stone Town' => 35,
            'Paje / Jambiani' => 55,
            'Nungwi / Kendwa' => 65,
            'Matemwe / Kiwengwa' => 60,
            'Michamvi' => 65,
            'Kizimkazi / Makunduchi' => 65,
        ];

        foreach ($prices as $destination => $price) {
            DB::table('transport_routes')
                ->where('pickup_location', 'Zanzibar International Airport (ZNZ)')
                ->where('destination', $destination)
                ->update(['van_price' => $price, 'updated_at' => now()]);
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const PICKUP = 'Zanzibar International Airport (ZNZ)';

    public function up(): void
    {
        DB::table('transport_routes')->update([
            'is_active' => false,
            'updated_at' => now(),
        ]);

        foreach ($this->routes() as $position => [$destination, $standardCarPrice]) {
            DB::table('transport_routes')->updateOrInsert(
                ['pickup_location' => self::PICKUP, 'destination' => $destination],
                [
                    'currency' => 'USD',
                    'sedan_price' => $standardCarPrice,
                    'suv_minivan_price' => 110,
                    'van_price' => null,
                    'minibus_price' => null,
                    'return_discount_percent' => 0,
                    'night_surcharge' => 0,
                    'night_start' => '23:00',
                    'night_end' => '06:00',
                    'child_seat_fee' => 0,
                    'is_bidirectional' => true,
                    'is_active' => true,
                    'sort_order' => $position + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    public function down(): void
    {
        // Transport prices remain editable through the admin area.
    }

    /** @return array<int, array{string, int}> */
    private function routes(): array
    {
        return [
            ['Stone Town', 20],
            ['Mtoni / Kibweni', 25],
            ['Bububu', 30],
            ['Mangrove Hotel Area', 40],
            ['Mangapwani / Sea Cliff', 50],
            ['Kiwengwa', 60],
            ['Pwani Mchangani', 65],
            ['Matemwe', 70],
            ['Kokotoni', 60],
            ['Nungwi / Kendwa', 75],
            ['Pongwe', 60],
            ['Chwaka / Uroa', 50],
            ['Tunguu', 40],
            ['Unguja Ukuu', 50],
            ['Jozani Forest', 50],
            ['Paje', 60],
            ['Bwejuu', 65],
            ['Dongwe', 75],
            ['Michamvi / Pingwe', 80],
            ['Jambiani', 75],
            ['Makunduchi', 75],
            ['Kizimkazi', 80],
            ['Fumba', 75],
        ];
    }
};

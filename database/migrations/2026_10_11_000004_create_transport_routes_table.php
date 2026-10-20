<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_location');
            $table->string('destination');
            $table->char('currency', 3)->default('USD');
            $table->decimal('sedan_price', 10, 2)->nullable();
            $table->decimal('suv_minivan_price', 10, 2)->nullable();
            $table->decimal('van_price', 10, 2)->nullable();
            $table->decimal('minibus_price', 10, 2)->nullable();
            $table->decimal('return_discount_percent', 5, 2)->default(0);
            $table->decimal('night_surcharge', 10, 2)->default(0);
            $table->time('night_start')->default('23:00');
            $table->time('night_end')->default('06:00');
            $table->decimal('child_seat_fee', 10, 2)->default(0);
            $table->boolean('is_bidirectional')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('transport_requests', function (Blueprint $table) {
            $table->foreignId('transport_route_id')->nullable()->after('reference')->constrained()->nullOnDelete();
            $table->decimal('estimated_price', 10, 2)->nullable()->after('vehicle_category');
            $table->char('estimated_currency', 3)->nullable()->after('estimated_price');
            $table->json('price_breakdown')->nullable()->after('estimated_currency');
        });

        $now = now();
        $routes = [
            ['Stone Town', 20, 25, 35],
            ['Paje / Jambiani', 40, 45, 55],
            ['Nungwi / Kendwa', 45, 50, 65],
            ['Matemwe / Kiwengwa', 40, 45, 60],
            ['Michamvi', 45, 50, 65],
            ['Kizimkazi / Makunduchi', 45, 50, 65],
        ];
        foreach ($routes as $index => [$destination, $sedan, $suv, $van]) {
            DB::table('transport_routes')->insert([
                'pickup_location' => 'Zanzibar International Airport (ZNZ)', 'destination' => $destination,
                'currency' => 'USD', 'sedan_price' => $sedan, 'suv_minivan_price' => $suv,
                'van_price' => $van, 'minibus_price' => null, 'return_discount_percent' => 5,
                'night_surcharge' => 5, 'night_start' => '23:00', 'night_end' => '06:00',
                'child_seat_fee' => 0, 'is_bidirectional' => true, 'is_active' => true,
                'sort_order' => $index + 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('transport_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transport_route_id');
            $table->dropColumn(['estimated_price', 'estimated_currency', 'price_breakdown']);
        });
        Schema::dropIfExists('transport_routes');
    }
};

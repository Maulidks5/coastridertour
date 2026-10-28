<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('booking_style_label')->nullable()->after('pricing_note');
        });

        $tour = DB::table('tours')->where('slug', 'safari-blue')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 120,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 195,
            'private_2_person_price' => 145,
            'private_3_person_price' => 130,
            'private_4_plus_person_price' => 120,
            'private_child_price' => 60,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'All-inclusive package · private transfer + shared dhow',
            'pricing_note' => 'All-inclusive per-person rate with private return transfer from covered South and South-East Coast hotels and the shared Safari Blue dhow experience. Stone Town, North, North-East and other pickup areas may require a transfer supplement. Private boat arrangements and groups above 10 are quoted separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other areas may require a confirmed transfer supplement.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->updateOrInsert(
            ['tour_id' => $tour->id, 'label' => 'Private return hotel transfer from covered South and South-East Coast areas'],
            ['is_included' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_inclusions')->where('tour_id', $tour->id)
            ->where('label', 'Private return transport from covered South and South-East Coast hotels')
            ->delete();
        DB::table('tour_inclusions')->updateOrInsert(
            ['tour_id' => $tour->id, 'label' => 'Private boat exclusivity unless specifically confirmed in your quote'],
            ['is_included' => false, 'sort_order' => 9, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is the dhow private?'],
            ['answer' => 'The published package includes a private hotel transfer and the shared Safari Blue dhow experience. A private boat can be arranged on request and is priced separately.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'safari-blue')->update([
            'price' => 119,
            'private_1_person_price' => 194,
            'private_2_person_price' => 143,
            'private_3_person_price' => 131,
            'private_4_plus_person_price' => 119,
            'private_child_price' => 58,
        ]);

        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('booking_style_label');
        });
    }
};

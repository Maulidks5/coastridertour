<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-day-hike')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 200,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 200,
            'private_2_person_price' => 200,
            'private_3_person_price' => 200,
            'private_4_plus_person_price' => 200,
            'private_child_price' => null,
            'private_infant_price' => null,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private guided day hike · from USD 200 per adult',
            'pricing_note' => 'From USD 200 per adult. The published estimate includes the guided Marangu Gate to Mandara Hut day hike, stated park fees, packed lunch, drinking water and return road transfer from central Moshi. Arusha, Kilimanjaro Airport and other pickup areas, groups above ten and bookings involving children receive a confirmed quote before payment.',
            'pickup_details' => 'Return pickup from central Moshi is included in the published estimate. Arusha, Kilimanjaro International Airport and other pickup areas are confirmed separately with any applicable transfer supplement before payment.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')
            ->where('tour_id', $tour->id)
            ->where('label', 'Confirmed return road transfer')
            ->update([
                'label' => 'Return road transfer from central Moshi',
                'updated_at' => now(),
            ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What does the USD 200 starting price include?'],
            ['answer' => 'The published adult estimate includes the guided Marangu Gate to Mandara Hut day hike, applicable park fees stated in your confirmation, packed lunch, drinking water and return transfer from central Moshi. Other pickup areas are confirmed separately.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Can children join the Kilimanjaro day hike?'],
            ['answer' => 'Requests involving children are reviewed individually based on age, fitness, route conditions and guide advice. Send each child’s age and we will confirm suitability and the final price before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-day-hike')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 0,
            'private_pricing_model' => 'quote_only',
            'private_1_person_price' => null,
            'private_2_person_price' => null,
            'private_3_person_price' => null,
            'private_4_plus_person_price' => null,
            'private_child_price' => null,
            'private_infant_price' => null,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => null,
            'pricing_note' => 'Request a personalised quote based on dates, group size, route, crew, park fees, accommodation and equipment needs.',
            'pickup_details' => 'Transfers are arranged from Kilimanjaro International Airport, Moshi or Arusha as listed in the confirmed itinerary.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')
            ->where('tour_id', $tour->id)
            ->where('label', 'Return road transfer from central Moshi')
            ->update([
                'label' => 'Confirmed return road transfer',
                'updated_at' => now(),
            ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What does the USD 200 starting price include?',
            'Can children join the Kilimanjaro day hike?',
        ])->delete();
    }
};

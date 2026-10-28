<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'nyerere-three-day-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 950,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'private_1_person_price' => null,
            'private_2_person_price' => null,
            'private_3_person_price' => null,
            'private_4_plus_person_price' => null,
            'private_child_price' => null,
            'private_infant_price' => null,
            'private_max_online_guests' => 7,
            'large_group_quote' => true,
            'booking_style_label' => 'Private 3-day Nyerere fly-in safari · from USD 950 per adult',
            'pricing_note' => 'From USD 950 per adult based on four adults travelling together from Zanzibar, sharing twin or double rooms on selected green-season dates. The proposed package includes confirmed-zone Zanzibar hotel transfers, scheduled return flights, Nyerere park and activity fees, private 4x4 game drives with a professional driver-guide, two nights in a confirmed mid-range camp or lodge on full board, one Rufiji River boat safari when safely available, and drinking water. The final price depends on season, flight seats, accommodation, room arrangement and group size. Smaller groups, solo travellers, children, single rooms, premium stays and additional activities receive a separate quote before payment.',
            'pickup_details' => 'For the Zanzibar fly-in package, hotel pickup and drop-off are arranged from the zone stated in your confirmed proposal. Exact times follow the domestic flight and activity schedules. Distant hotel zones, Dar es Salaam road departures and alternative start points are priced separately before payment.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What does the USD 950 starting price include?'],
            ['answer' => 'For four adults travelling together from Zanzibar, sharing twin or double rooms on selected green-season dates, the starting price includes confirmed-zone hotel transfers, scheduled return flights, Nyerere park and activity fees, private guided 4x4 game drives, two nights in a mid-range camp or lodge on full board, one Rufiji River boat safari when safely available, and drinking water.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Why is the final Nyerere price confirmed separately?'],
            ['answer' => 'Flight fares, lodge rates and park activity availability change by season and date. Group size, room arrangement, pickup zone and the ages of children can also affect the total. River and walking activities remain subject to weather, water level, safety and operator confirmation. We send the exact itinerary and final price before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'nyerere-three-day-safari')->first();
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
            'pricing_note' => 'Request a personalised quote. The final price depends on travel dates, group size, transport, park fees, accommodation and live availability. Nothing is charged until you approve the itinerary.',
            'pickup_details' => 'Zanzibar and Dar es Salaam departures can be arranged. Final pickup times follow confirmed flights, accommodation and activity schedules.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What does the USD 950 starting price include?',
            'Why is the final Nyerere price confirmed separately?',
        ])->delete();
    }
};

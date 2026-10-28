<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'mikumi-two-day-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 1000,
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
            'booking_style_label' => 'Private 2-day Mikumi fly-in safari · from USD 1,000 per adult',
            'pricing_note' => 'From USD 1,000 per adult based on four adults travelling together from Zanzibar and sharing twin or double rooms. The proposed package includes Zanzibar hotel transfers from the confirmed zone, scheduled return flights, Mikumi park fees, a private 4x4 with a professional driver-guide, one night in a confirmed mid-range lodge or camp, meals from lunch on Day 1 through lunch on Day 2, and drinking water. The final price depends on travel date, flight seats, lodge availability, room arrangement and group size. Smaller groups, solo travellers, children, single rooms and premium accommodation receive a separate quote before payment.',
            'pickup_details' => 'For the Zanzibar fly-in package, hotel pickup and drop-off are arranged from the zone stated in your confirmed proposal. Exact times follow the domestic flight schedule. Distant hotel zones, Dar es Salaam road departures and alternative start points are priced separately before payment.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What does the USD 1,000 starting price include?'],
            ['answer' => 'For four adults travelling together from Zanzibar and sharing twin or double rooms, the starting price includes confirmed-zone hotel transfers, scheduled return flights, Mikumi park fees, a private 4x4 and professional driver-guide, one night in a mid-range lodge or camp, meals from lunch on Day 1 through lunch on Day 2, and drinking water.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Why is the final two-day Mikumi price confirmed separately?'],
            ['answer' => 'Flight fares, room availability and lodge rates change by date. Group size, room arrangement, hotel pickup zone and the ages of children can also affect the total. We confirm the exact lodge, meal plan, flights and final price before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'mikumi-two-day-safari')->first();
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
            'pickup_details' => 'Start from Zanzibar or Dar es Salaam. Hotel, airport and park transfers are arranged according to the transport option and accommodation shown in your final itinerary.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What does the USD 1,000 starting price include?',
            'Why is the final two-day Mikumi price confirmed separately?',
        ])->delete();
    }
};

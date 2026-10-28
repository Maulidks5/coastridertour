<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'mikumi-day-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 500,
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
            'booking_style_label' => 'Private Mikumi fly-in day safari · from USD 500 per adult',
            'pricing_note' => 'From USD 500 per adult based on at least two adults travelling together from Zanzibar. The proposed package includes scheduled return flights, the confirmed Zanzibar transfer, Mikumi park fees, a private 4x4 game drive with a professional driver-guide, lunch and drinking water. The final price depends on travel date, flight seats, hotel pickup area and group size. Solo travellers, children, Dar es Salaam road departures and larger groups receive a separate confirmed quote before payment.',
            'pickup_details' => 'For the Zanzibar fly-in package, pickup and drop-off are arranged from the hotel zone stated in your confirmed proposal. Exact pickup time follows the domestic flight schedule. Transfers from distant hotel zones and road departures from Dar es Salaam are priced separately before payment.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What does the USD 500 starting price include?'],
            ['answer' => 'For at least two adults travelling together from Zanzibar, the starting price includes scheduled return flights, the confirmed Zanzibar transfer, Mikumi park fees, a private 4x4 game drive with a professional driver-guide, lunch and drinking water. Your proposal lists every inclusion before payment.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Why is the final Mikumi day-safari price confirmed separately?'],
            ['answer' => 'Domestic flight prices and seat availability can change by date. Your group size, hotel pickup zone, private vehicle requirements and the ages of children can also affect the total. We verify these details and send one final price before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'mikumi-day-safari')->first();
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
            'pickup_details' => 'Pickup is arranged from your confirmed Zanzibar hotel, Zanzibar airport or Dar es Salaam address. Exact timing follows the flight or road itinerary in your final confirmation.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What does the USD 500 starting price include?',
            'Why is the final Mikumi day-safari price confirmed separately?',
        ])->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'serengeti-ngorongoro-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 1650,
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
            'booking_style_label' => 'Private 4-day Serengeti & Ngorongoro safari · from USD 1,650 per adult',
            'pricing_note' => 'From USD 1,650 per adult for a four-day, three-night private safari based on four adults travelling together from Arusha, sharing twin or double rooms on selected low- or shoulder-season dates. The proposed package includes a private 4x4 and professional driver-guide, three nights in confirmed mid-range camps or lodges, Serengeti and Ngorongoro park and conservation fees, one Ngorongoro Crater descent, meals listed in the itinerary, drinking water and agreed Arusha transfers. Zanzibar and other domestic flights, premium lodging, single rooms, children and three- or five-day variations are quoted separately before payment.',
            'pickup_details' => 'The published starting price begins and ends in Arusha and includes the transfers stated in your confirmed itinerary. Kilimanjaro Airport transfers, Zanzibar connections, Serengeti airstrip flights and alternative start or finish points are added to the final proposal before payment.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What does the USD 1,650 starting price include?'],
            ['answer' => 'For four adults travelling together from Arusha on a four-day, three-night itinerary, sharing twin or double rooms on selected low- or shoulder-season dates, the starting price includes a private 4x4 and driver-guide, three nights in mid-range accommodation, applicable Serengeti and Ngorongoro fees, one crater descent, listed meals, drinking water and agreed Arusha transfers.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are Zanzibar flights included in the starting price?'],
            ['answer' => 'No. The published starting price begins and ends in Arusha. We can add Zanzibar-to-Arusha, Kilimanjaro or Serengeti airstrip flights with the required transfers, subject to the schedule, baggage rules and live fare for your dates.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Why is the final Serengeti and Ngorongoro price confirmed separately?'],
            ['answer' => 'The final price changes with season, safari length, group size, lodge availability, room arrangement and transport. Migration routing and domestic flights can also change the itinerary. We confirm the exact route, accommodation and final total before payment.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'serengeti-ngorongoro-safari')->first();
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
            'pickup_details' => 'The itinerary can start from Arusha, Kilimanjaro Airport or an agreed domestic airstrip. Zanzibar connections can be added to the confirmed package.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What does the USD 1,650 starting price include?',
            'Are Zanzibar flights included in the starting price?',
            'Why is the final Serengeti and Ngorongoro price confirmed separately?',
        ])->delete();
    }
};

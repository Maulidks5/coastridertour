<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'northern-circuit-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 2300,
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
            'booking_style_label' => 'Private 6-day Northern Circuit safari · from USD 2,300 per adult',
            'pricing_note' => 'From USD 2,300 per adult for a six-day, five-night private safari based on four adults travelling together from Arusha, sharing twin or double rooms on selected low- or shoulder-season dates. The proposed route includes Tarangire, Lake Manyara or an equivalent seasonal alternative, Serengeti and Ngorongoro Crater, with a private 4x4 and professional driver-guide, five nights in confirmed mid-range camps or lodges, applicable park and conservation fees, one crater descent, meals listed in the itinerary, drinking water and agreed Arusha transfers. Zanzibar and other domestic flights, premium lodging, single rooms, children and four-, five- or seven-day variations are quoted separately before payment.',
            'pickup_details' => 'The published starting price begins and ends in Arusha and includes the hotel or local transfer stated in your confirmed itinerary. Kilimanjaro Airport transfers, Zanzibar connections, Serengeti airstrip flights and alternative start or finish points are added to the final proposal before payment.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What does the USD 2,300 starting price include?'],
            ['answer' => 'For four adults travelling together from Arusha on a six-day, five-night itinerary, sharing twin or double rooms on selected low- or shoulder-season dates, the starting price includes a private 4x4 and driver-guide, five nights in mid-range accommodation, applicable park and conservation fees, one Ngorongoro Crater descent, listed meals, drinking water and agreed Arusha transfers.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Which route is covered by the starting price?'],
            ['answer' => 'The starting package is built around a six-day private route linking Tarangire, Lake Manyara or the strongest seasonal alternative, Serengeti and Ngorongoro Crater. We may recommend adjusting the order or substituting a park when season, road time or lodge availability would improve the safari.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are Zanzibar flights included in the starting price?'],
            ['answer' => 'No. The published starting price begins and ends in Arusha. We can add Zanzibar flights, airport transfers or a Serengeti fly-out sector using the schedule, baggage rules and live fare available for your dates.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Why is the final Northern Circuit price confirmed separately?'],
            ['answer' => 'The final price changes with season, safari length, group size, lodge availability, room arrangement and transport. Park routing and domestic flights can also change the package. We confirm the exact itinerary, accommodation and final total before payment.', 'sort_order' => 9, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'northern-circuit-safari')->first();
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
            'pickup_details' => 'Start and finish points are tailored around Arusha, Kilimanjaro Airport or domestic flight connections. Zanzibar extensions can be included.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What does the USD 2,300 starting price include?',
            'Which route is covered by the starting price?',
            'Are Zanzibar flights included in the starting price?',
            'Why is the final Northern Circuit price confirmed separately?',
        ])->delete();
    }
};

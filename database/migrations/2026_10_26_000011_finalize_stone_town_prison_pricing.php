<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-prison-island')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Stone Town & Prison Island Tour',
            'excerpt' => 'Explore Stone Town’s heritage and visit Prison Island’s giant tortoises during one privately guided experience with hotel transfer and boat.',
            'description' => "Begin with private hotel pickup and explore UNESCO-listed Stone Town with a knowledgeable local guide. Walk through its narrow streets, carved doors, market atmosphere and selected historic landmarks before boarding a private boat from the waterfront.\n\nCross to Prison Island for a guided visit to its historic sites and giant Aldabra tortoise sanctuary. The activity order may change according to sea conditions, opening hours, traffic and the most practical pickup route.",
            'duration' => 'Full Day',
            'location' => 'Stone Town & Prison Island',
            'price' => 105,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 220,
            'private_2_person_price' => 130,
            'private_3_person_price' => 120,
            'private_4_plus_person_price' => 105,
            'private_child_price' => 55,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private heritage combo · transfer + private boat + fees',
            'pricing_note' => 'All-inclusive private package for up to ten guests. Rates include return transport from covered South and South-East Coast hotels, professional guide, private boat, Prison Island entrance, conservation fees and applicable Stone Town itinerary fees. Lunch, other pickup areas and groups above ten are confirmed separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other pickup areas may receive a lower or adjusted transfer rate before payment.',
            'meta_title' => 'Private Stone Town & Prison Island Tour | Coast Rider Tour',
            'meta_description' => 'Book a private Stone Town and Prison Island tour with hotel transfer, local guide, private boat, island entrance and conservation fees included.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Professional local Stone Town and island guide',
            'Privately guided Stone Town heritage walk',
            'Private return boat crossing to Prison Island',
            'Prison Island entrance and applicable conservation fees',
            'Guided historic-island and giant-tortoise sanctuary visit',
            'Applicable confirmed Stone Town itinerary fees',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Lunch, snacks and additional drinks',
            'Optional museums not listed in the confirmed itinerary',
            'Snorkeling equipment unless confirmed before the tour',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are the boat and island fees included?'],
            ['answer' => 'Yes. The published package includes a private return boat, Prison Island entrance and applicable conservation fees.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is lunch included?'],
            ['answer' => 'Lunch is not included in the standard package. We can recommend a restaurant or add a confirmed meal arrangement before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'stone-town-prison-island')->update([
            'price' => 0,
            'private_pricing_model' => 'quote_only',
            'private_1_person_price' => null,
            'private_2_person_price' => null,
            'private_3_person_price' => null,
            'private_4_plus_person_price' => null,
            'private_child_price' => null,
            'private_infant_price' => null,
            'private_max_online_guests' => 10,
            'booking_style_label' => null,
        ]);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-jozani-forest')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Stone Town & Jozani Forest Tour',
            'excerpt' => 'Combine Stone Town’s cultural heritage with a guided Jozani Forest walk and Zanzibar red colobus experience in one private day.',
            'description' => "Begin with private hotel pickup and explore UNESCO-listed Stone Town with a knowledgeable local guide. Walk through its historic lanes, carved doors, market atmosphere and selected heritage landmarks before travelling privately to Jozani Chwaka Bay National Park.\n\nAt Jozani, follow the required park guide through suitable forest trails while learning about the ecosystem and looking for endemic Zanzibar red colobus monkeys. The mangrove boardwalk is included when access and conditions allow; wildlife sightings cannot be guaranteed.",
            'duration' => 'Full Day',
            'location' => 'Stone Town & Jozani Chwaka Bay',
            'price' => 80,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 185,
            'private_2_person_price' => 115,
            'private_3_person_price' => 90,
            'private_4_plus_person_price' => 80,
            'private_child_price' => 40,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private culture & nature combo · transfer + guides + fees',
            'pricing_note' => 'All-inclusive private package for up to ten guests. Rates include return transport from covered South and South-East Coast hotels, Stone Town guide, applicable itinerary fees, Jozani park entrance and the required park guide. Lunch, other pickup areas and groups above ten are confirmed separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other pickup areas may receive a lower or adjusted transfer rate before payment.',
            'meta_title' => 'Private Stone Town & Jozani Forest Tour | Coast Rider Tour',
            'meta_description' => 'Book a private Stone Town and Jozani Forest tour with hotel transfer, local guides, heritage itinerary fees and national park entrance included.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private air-conditioned transport between activities',
            'Professional local Stone Town guide',
            'Privately guided Stone Town heritage walk',
            'Applicable fees on the confirmed Stone Town itinerary',
            'Jozani National Park entrance and required park guide',
            'Guided red-colobus forest and mangrove walk when accessible',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Lunch, snacks and additional drinks',
            'Optional museums not listed in the confirmed itinerary',
            'Guaranteed wildlife sightings or interaction',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are the Jozani entrance and guide included?'],
            ['answer' => 'Yes. Jozani National Park entrance and the required local park guide are included in the published package price.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are red colobus sightings guaranteed?'],
            ['answer' => 'No. Red colobus monkeys are free-ranging wildlife. They are frequently observed, but sightings and proximity cannot be guaranteed.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is lunch included?'],
            ['answer' => 'Lunch is not included in the standard package. We can arrange a confirmed meal stop before payment.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'stone-town-jozani-forest')->update([
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

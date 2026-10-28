<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-spice-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Stone Town & Spice Farm Tour',
            'excerpt' => 'Combine Stone Town’s heritage with an interactive spice-farm visit during one privately guided full-day cultural experience.',
            'description' => "Begin with private hotel pickup and discover UNESCO-listed Stone Town with a knowledgeable local guide. Walk through its narrow streets, carved doors, market atmosphere and selected historic landmarks before travelling privately to a working spice farm.\n\nAt the farm, see, smell and taste seasonal spices, fruits and useful tropical plants while learning about their traditional uses. The activity order may change with opening hours, traffic, seasonal availability and your confirmed pickup location.",
            'duration' => 'Full Day',
            'location' => 'Stone Town & Zanzibar Spice Farms',
            'price' => 70,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 185,
            'private_2_person_price' => 110,
            'private_3_person_price' => 85,
            'private_4_plus_person_price' => 70,
            'private_child_price' => 35,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private cultural combo · transfer + guides + fees',
            'pricing_note' => 'All-inclusive private cultural package for up to ten guests. Rates include return transport from covered South and South-East Coast hotels, professional guides, confirmed itinerary entrance fees and seasonal tasting. Lunch, other pickup areas and groups above ten are confirmed separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other pickup areas may receive a lower or adjusted transfer rate before payment.',
            'meta_title' => 'Private Stone Town & Spice Farm Tour | Coast Rider Tour',
            'meta_description' => 'Book a private Stone Town and Spice Farm tour with hotel transport, professional guides, entrance fees and seasonal tasting included.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private air-conditioned transport between activities',
            'Professional local Stone Town guide and spice-farm host',
            'Privately guided Stone Town heritage walk',
            'Applicable entrance fees on the confirmed itinerary',
            'Guided spice-farm walk with seasonal tasting',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Lunch, cooking class and additional drinks',
            'Freddie Mercury Museum or attractions not confirmed in the itinerary',
            'Spices, souvenirs and other personal purchases',
            'Tips and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is lunch included?'],
            ['answer' => 'Lunch is not included in the standard package. A Swahili lunch or cooking experience can be added and confirmed before payment.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are entrance fees included?'],
            ['answer' => 'Yes. Spice-farm entry and applicable fees for sites listed in your confirmed Stone Town itinerary are included. Optional museums must be added before confirmation.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'stone-town-spice-tour')->update([
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

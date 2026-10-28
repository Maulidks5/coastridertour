<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'spice-jozani-forest')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Spice Farm & Jozani Forest Tour',
            'excerpt' => 'Discover Zanzibar’s spice-growing heritage and protected red-colobus habitat during one privately arranged nature experience.',
            'description' => "Begin with private hotel pickup and explore a working spice farm with a local host. See, smell and taste seasonal spices, fruits and useful tropical plants while learning about their traditional uses and Zanzibar's spice heritage.\n\nContinue privately to Jozani Chwaka Bay National Park for a walk with the required park guide. Explore suitable forest trails, look for endemic Zanzibar red colobus monkeys and visit the mangrove boardwalk when access allows. Wildlife sightings cannot be guaranteed.",
            'duration' => 'Full Day',
            'location' => 'Zanzibar Spice Farms & Jozani Forest',
            'price' => 65,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 175,
            'private_2_person_price' => 100,
            'private_3_person_price' => 80,
            'private_4_plus_person_price' => 65,
            'private_child_price' => 35,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private nature combo · transfer + farm & park guides',
            'pricing_note' => 'All-inclusive private package for up to ten guests. Rates include return transport from covered South and South-East Coast hotels, spice-farm entrance and tasting, Jozani park entrance and the required park guide. Lunch, other pickup areas and groups above ten are confirmed separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other pickup areas may receive a lower or adjusted transfer rate before payment.',
            'meta_title' => 'Private Spice Farm & Jozani Forest Tour | Coast Rider Tour',
            'meta_description' => 'Book a private Spice Farm and Jozani Forest tour with hotel transfer, farm tasting, national park entrance and local guides included.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private air-conditioned transport between activities',
            'Professional spice-farm host and required Jozani park guide',
            'Spice-farm entrance and guided seasonal plant walk',
            'Seasonal spice, fruit and coconut tasting when available',
            'Jozani National Park entrance fee',
            'Guided red-colobus forest and mangrove walk when accessible',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Lunch, cooking class and additional drinks',
            'Guaranteed wildlife sightings or interaction',
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
            ['tour_id' => $tour->id, 'question' => 'Are both activity entrance fees included?'],
            ['answer' => 'Yes. Spice-farm entry and Jozani National Park entrance, including the required park guide, are included in the published package.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is lunch included?'],
            ['answer' => 'Lunch is not included in the standard package. A Swahili lunch or cooking experience can be added and confirmed before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are red colobus sightings guaranteed?'],
            ['answer' => 'No. Red colobus monkeys are free-ranging wildlife. They are frequently observed, but sightings and proximity cannot be guaranteed.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'spice-jozani-forest')->update([
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

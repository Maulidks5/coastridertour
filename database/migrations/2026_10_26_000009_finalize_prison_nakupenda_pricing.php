<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'prison-island-nakupenda')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Prison Island & Nakupenda Sandbank',
            'excerpt' => 'Meet Prison Island’s giant tortoises, then swim and enjoy a seafood BBQ lunch at Nakupenda during a private full-day boat experience.',
            'description' => "Begin with a private hotel transfer to the Stone Town coast and board a private boat for Prison Island. Explore the island with a local guide, learn about its history and visit the giant Aldabra tortoise sanctuary before continuing to Nakupenda Sandbank.\n\nAt Nakupenda, swim, relax and enjoy seasonal tropical fruit, refreshments and a freshly prepared seafood BBQ lunch. The itinerary order, sandbank size and time at each stop depend on tide, weather, sea conditions and local opening hours.",
            'duration' => 'Full Day',
            'location' => 'Prison Island & Nakupenda Sandbank',
            'price' => 95,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 200,
            'private_2_person_price' => 130,
            'private_3_person_price' => 110,
            'private_4_plus_person_price' => 95,
            'private_child_price' => 55,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private island combo · boat + transfer + BBQ lunch',
            'pricing_note' => 'All-inclusive private package for up to eight guests. Rates include private return transport from covered hotel areas, private boat, guide, Prison Island entrance and conservation fees, snorkeling equipment, BBQ lunch and refreshments. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when booking so we can confirm the tide-based departure time and any remote-area transfer supplement before payment.',
            'meta_title' => 'Private Prison Island & Nakupenda Tour with Lunch | Coast Rider',
            'meta_description' => 'Book a private Prison Island and Nakupenda Sandbank tour with hotel transfer, private boat, all entrance fees, snorkeling gear and BBQ lunch.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private boat with professional guide and local crew',
            'Prison Island entrance and applicable marine conservation fees',
            'Guided historic-island and giant-tortoise sanctuary visit',
            'Nakupenda Sandbank swimming and relaxation time',
            'Seafood BBQ lunch with a confirmed alternative on request',
            'Seasonal fruit, drinking water and soft drinks',
            'Mask, snorkel, fins, life jacket, taxes and service charges',
        ];
        $excluded = [
            'Alcoholic drinks and special meals not confirmed in advance',
            'Guaranteed snorkeling visibility or wildlife sightings',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are all island and conservation fees included?'],
            ['answer' => 'Yes. Prison Island entrance and the applicable marine conservation fees are included in the published package price.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is lunch included?'],
            ['answer' => 'Yes. A seafood BBQ lunch, seasonal fruit, drinking water and soft drinks are included. Share dietary needs and allergies before confirmation.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'prison-island-nakupenda')->update([
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

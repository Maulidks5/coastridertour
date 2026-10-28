<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'nakupenda-beach')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Nakupenda Sandbank Escape',
            'excerpt' => 'Cruise privately to Nakupenda Sandbank for swimming, relaxation and a freshly prepared seafood BBQ lunch planned around the tide.',
            'description' => "Begin with a private transfer from your confirmed hotel to the Stone Town coast, then board a private local boat for Nakupenda Sandbank. Swim, relax beneath the shade and enjoy seasonal tropical fruit, refreshments and a freshly prepared seafood BBQ lunch.\n\nNakupenda is a natural tidal sandbank rather than a permanent island. Its size, arrival time and visit duration change with the tide, weather and sea conditions, so the crew confirms the safest and most enjoyable schedule for the day.",
            'duration' => 'Full Day',
            'location' => 'Nakupenda Sandbank, Stone Town Coast',
            'price' => 75,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 150,
            'private_2_person_price' => 100,
            'private_3_person_price' => 85,
            'private_4_plus_person_price' => 75,
            'private_child_price' => 45,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private sandbank package · boat + transfer + BBQ lunch',
            'pricing_note' => 'All-inclusive private package for up to eight guests. Rates include private return transport from covered hotel areas, a private boat, guide, snorkeling equipment, seafood BBQ lunch, refreshments and applicable fees. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when booking so we can confirm the tide-based departure time and any remote-area transfer supplement before payment.',
            'meta_title' => 'Private Nakupenda Sandbank Tour with Lunch | Coast Rider Tour',
            'meta_description' => 'Book a private Nakupenda Sandbank tour with hotel transfer, private boat, guide, snorkeling equipment, seafood BBQ lunch and refreshments.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private return boat journey from the Stone Town coast',
            'Professional local guide and boat crew',
            'Seafood BBQ lunch with a confirmed alternative on request',
            'Seasonal tropical fruit, drinking water and soft drinks',
            'Mask, snorkel, fins and life jacket',
            'Applicable marine fees, government taxes and service charges',
        ];
        $excluded = [
            'Alcoholic drinks and special meals not confirmed in advance',
            'Prison Island visit or entrance fees',
            'Guaranteed snorkeling visibility or marine-life sightings',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are the boat and lunch private?'],
            ['answer' => 'The boat is reserved for your confirmed group. The crew prepares and serves your included BBQ lunch at the sandbank according to the day’s tide and conditions.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Can dietary requirements be accommodated?'],
            ['answer' => 'Vegetarian and selected non-seafood alternatives can usually be arranged. Tell us about allergies and dietary requirements before the booking is confirmed.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'nakupenda-beach')->update([
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'prison-island')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Prison Island & Giant Tortoises Tour',
            'excerpt' => 'Cross from Stone Town by private boat to explore Prison Island with a local guide and meet its famous giant Aldabra tortoises.',
            'description' => "Begin with a private transfer from your confirmed hotel to the Stone Town coast, then take a private boat across to Prison Island, also known as Changuu Island. Your local guide will introduce the island's history before taking you to the giant Aldabra tortoise sanctuary.\n\nThis is a relaxed half-day experience with time for photographs and coastal views. The exact departure time and crossing are planned around sea conditions, island opening hours and the most practical pickup route.",
            'duration' => 'Half Day',
            'location' => 'Prison Island, Stone Town Coast',
            'price' => 75,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 145,
            'private_2_person_price' => 100,
            'private_3_person_price' => 85,
            'private_4_plus_person_price' => 75,
            'private_child_price' => 40,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private island package · hotel transfer + private boat',
            'pricing_note' => 'All-inclusive private package for up to eight guests. Rates include private return transport from covered hotel areas, a private boat, local guide, island entrance and applicable conservation fees. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when booking so we can confirm the pickup time and any remote-area transfer supplement before payment.',
            'meta_title' => 'Private Prison Island & Giant Tortoises Tour | Coast Rider Tour',
            'meta_description' => 'Book an all-inclusive private Prison Island tour with hotel transfer, private boat, local guide, entrance fees and a visit to the giant tortoises.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private return boat crossing from the Stone Town coast',
            'Professional local guide and boat crew',
            'Prison Island entrance and applicable conservation fees',
            'Guided visit to the giant tortoise sanctuary and historic sites',
            'Life jackets, drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Meals, snacks and alcoholic drinks',
            'Optional snorkeling unless confirmed before the tour',
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
            ['answer' => 'Yes. The published package includes a private return boat, Prison Island entrance and the applicable conservation fees.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is hotel pickup included?'],
            ['answer' => 'Yes. Private return transport is included from covered Zanzibar pickup areas. A supplement may apply to remote locations and is confirmed before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'prison-island')->update([
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

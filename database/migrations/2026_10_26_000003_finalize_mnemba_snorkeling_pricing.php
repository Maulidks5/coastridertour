<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'mnemba-island')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Mnemba Atoll Snorkeling Experience',
            'excerpt' => 'Explore the reefs around Mnemba by private boat with a snorkeling guide, equipment, refreshments and return hotel transfer.',
            'description' => "Start with a private transfer from your confirmed hotel to the Matemwe or Muyuni departure area, then board a private boat for a guided snorkeling journey around the reefs near Mnemba Island. Swim above coral, look for tropical marine life and enjoy seasonal fruit and refreshments with your local crew.\n\nMnemba Island itself is private and landing is not permitted. Dolphins may be seen along the route, but sightings and interaction are never guaranteed. The crew follows sea conditions and responsible wildlife practices when planning the morning.",
            'duration' => 'Half Day',
            'location' => 'Matemwe & Mnemba reef',
            'price' => 65,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 160,
            'private_2_person_price' => 95,
            'private_3_person_price' => 75,
            'private_4_plus_person_price' => 65,
            'private_child_price' => 40,
            'private_infant_price' => 0,
            'private_max_online_guests' => 6,
            'large_group_quote' => true,
            'booking_style_label' => 'Private snorkeling package · hotel transfer + private boat',
            'pricing_note' => 'All-inclusive private package for up to six guests. Rates include a private boat, snorkeling guide, equipment, conservation fee and return transfer from covered hotel areas. Groups above six and pickup areas requiring extra travel receive a confirmed quote.',
            'pickup_details' => 'Return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when booking so we can confirm pickup time and whether a remote-area transfer supplement applies.',
            'meta_title' => 'Private Mnemba Snorkeling Tour Zanzibar | Coast Rider Tour',
            'meta_description' => 'Book a private Mnemba reef snorkeling package with hotel transfer, private boat, guide, equipment, conservation fees and refreshments.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private boat with captain and local snorkeling guide',
            'Mask, snorkel, fins and life jacket',
            'Marine conservation and activity fees',
            'Seasonal tropical fruit, drinking water and soft drinks',
            'Government taxes and service charges',
        ];
        $excluded = [
            'Lunch or seafood barbecue',
            'Landing on private Mnemba Island',
            'Guaranteed dolphin sightings or interaction',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is the boat private?'],
            ['answer' => 'Yes. The published package includes a private boat for your confirmed group, together with a private hotel transfer from covered pickup areas.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is the conservation fee included?'],
            ['answer' => 'Yes. The applicable marine conservation and activity fees are included in the published package price.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'mnemba-island')->update([
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'jozani-forest')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Jozani Forest & Red Colobus Tour',
            'excerpt' => 'Explore Jozani Forest with private hotel transport, park entrance and a guided walk through red-colobus and mangrove habitat.',
            'description' => "Visit Jozani Chwaka Bay National Park for a guided nature walk through one of Zanzibar's most important protected habitats. Learn about the forest ecosystem and conservation while looking for endemic Zanzibar red colobus monkeys from a respectful distance.\n\nThe park-guided route may include the mangrove boardwalk when access and conditions allow. The monkeys are free-ranging wildlife, so sightings cannot be guaranteed and guests must follow the park guide's instructions at all times.",
            'duration' => 'Half Day',
            'location' => 'Jozani Chwaka Bay, Zanzibar',
            'price' => 35,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 90,
            'private_2_person_price' => 50,
            'private_3_person_price' => 40,
            'private_4_plus_person_price' => 35,
            'private_child_price' => 20,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Nature package · private transfer + park-guided walk',
            'pricing_note' => 'All-inclusive half-day package for up to ten guests. Rates include private return transport from covered South and South-East Coast hotels, park entrance and the required park-guided forest walk. Other pickup areas and groups above ten receive a confirmed adjustment.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other pickup areas may receive an adjusted transfer rate before payment.',
            'meta_title' => 'Jozani Forest & Red Colobus Tour Zanzibar | Coast Rider Tour',
            'meta_description' => 'Book a Jozani Forest tour with private hotel transport, national park entrance and a guided red colobus and mangrove forest experience.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Jozani Chwaka Bay National Park entrance fee',
            'Required local park guide for the forest visit',
            'Guided red colobus and forest habitat walk',
            'Mangrove boardwalk when access and conditions allow',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Food and additional drinks',
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
            ['tour_id' => $tour->id, 'question' => 'Is the park entrance fee included?'],
            ['answer' => 'Yes. Jozani Chwaka Bay National Park entrance and the required local park guide are included in the published package price.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are red colobus sightings guaranteed?'],
            ['answer' => 'No. Red colobus monkeys are free-ranging wildlife. They are frequently observed, but sightings and proximity can never be guaranteed.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'jozani-forest')->update([
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

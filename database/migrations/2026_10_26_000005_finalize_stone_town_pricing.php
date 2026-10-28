<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Historic Stone Town Walking Tour',
            'excerpt' => 'Discover Stone Town privately with a knowledgeable local guide, return hotel transport and applicable heritage entrance fees included.',
            'description' => "Explore the stories, architecture and everyday life of UNESCO-listed Stone Town on a privately guided walking tour. Follow the narrow streets past carved doors, Darajani Market, the Old Fort and selected places connected to Zanzibar's trading and cultural heritage.\n\nYour guide adapts the pace and route to your group. The exact sites visited depend on opening hours and local conditions; entrance to the Freddie Mercury Museum and any attraction not listed in the confirmed itinerary remains optional.",
            'duration' => 'Half Day',
            'location' => 'Stone Town, Zanzibar',
            'price' => 60,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 150,
            'private_2_person_price' => 90,
            'private_3_person_price' => 70,
            'private_4_plus_person_price' => 60,
            'private_child_price' => 30,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private heritage tour · hotel transfer + local guide',
            'pricing_note' => 'All-inclusive private package for up to ten guests. Rates include return transfer from covered South and South-East Coast hotels, a professional local guide and applicable entrance fees on the confirmed itinerary. Other pickup areas and groups above ten receive a confirmed adjustment.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other pickup areas may receive a lower or adjusted transfer rate before payment.',
            'meta_title' => 'Private Historic Stone Town Walking Tour | Coast Rider Tour',
            'meta_description' => 'Book a private Stone Town heritage tour with hotel transport, professional local guide, drinking water and applicable itinerary entrance fees.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Professional local Stone Town guide',
            'Private guided heritage walk adapted to your group',
            'Darajani Market, Old Fort and selected historic landmarks',
            'Applicable entrance fees on the confirmed itinerary',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Meals, snacks and alcoholic drinks',
            'Freddie Mercury Museum and attractions not listed in the confirmed itinerary',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Which entrance fees are included?'],
            ['answer' => 'Applicable fees for attractions listed in your confirmed itinerary are included. Optional sites such as the Freddie Mercury Museum are paid separately unless added before confirmation.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is this a private tour?'],
            ['answer' => 'Yes. The walking tour and return hotel transfer are arranged privately for your confirmed group.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'stone-town-tour')->update([
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

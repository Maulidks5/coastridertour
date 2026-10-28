<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'spice-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Zanzibar Spice Farm Tour',
            'excerpt' => 'Explore a working spice farm privately with hotel transfer, local guides and seasonal spice and tropical-fruit tasting included.',
            'description' => "Visit a working Zanzibar spice farm and discover how cloves, vanilla, cinnamon, nutmeg, tropical fruits and useful local plants grow. Your farm host leads an interactive walk focused on their scents, flavours, traditional uses and the island's spice-trade heritage.\n\nThe plants and fruits available naturally change with the season. The standard half-day package includes seasonal tasting but not lunch; a Swahili lunch or cooking experience can be arranged separately before confirmation.",
            'duration' => 'Half Day',
            'location' => 'Zanzibar Spice Farms',
            'price' => 40,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 135,
            'private_2_person_price' => 75,
            'private_3_person_price' => 50,
            'private_4_plus_person_price' => 40,
            'private_child_price' => 20,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private spice experience · hotel transfer + farm guide',
            'pricing_note' => 'All-inclusive private half-day package for up to ten guests. Rates include return transport from covered South and South-East Coast hotels, farm entrance, guides and seasonal tasting. Lunch, cooking experiences, other pickup areas and groups above ten are confirmed separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking. Stone Town, North, North-East and other areas may receive a lower or adjusted transfer rate before payment.',
            'meta_title' => 'Private Zanzibar Spice Farm Tour | Coast Rider Tour',
            'meta_description' => 'Book a private Zanzibar Spice Farm Tour with hotel transfer, local farm guides, entrance fees and seasonal spice and fruit tasting.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Professional local guide and spice-farm host',
            'Private guided walk through a working spice farm',
            'Applicable spice-farm entrance and activity fees',
            'Seasonal spice, fruit and coconut tasting when available',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Swahili lunch, cooking class and additional drinks',
            'Spices, soaps and other personal purchases',
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
            ['answer' => 'Lunch is not included in the standard half-day package. A Swahili lunch or cooking experience can be added and confirmed before payment.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Are tasting and entrance fees included?'],
            ['answer' => 'Yes. Farm entrance and seasonal spice, fruit or coconut tasting are included when available. The selection changes naturally throughout the year.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'spice-tour')->update([
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

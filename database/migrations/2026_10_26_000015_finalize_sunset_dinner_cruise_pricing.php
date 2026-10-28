<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'private-sunset-dinner-cruise')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Private Zanzibar Sunset Dinner Cruise',
            'excerpt' => 'Celebrate a special evening aboard a private vessel with coastal sunset views, attentive local service and a confirmed dinner menu.',
            'description' => "Begin with private hotel transfer to the confirmed departure point and board a vessel reserved exclusively for your group. Relax with Zanzibar coastal views and refreshments while the crew follows the safest route for the evening.\n\nEnjoy dinner according to the menu confirmed before payment, with dietary requirements arranged in advance. Vessel type, departure point and route depend on availability and sea conditions; sunset colours and visibility cannot be guaranteed.",
            'duration' => 'Half Day (Evening)',
            'location' => 'Zanzibar Coast',
            'price' => 135,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 525,
            'private_2_person_price' => 263,
            'private_3_person_price' => 175,
            'private_4_plus_person_price' => 135,
            'private_child_price' => 50,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Premium private cruise · transfer + dinner + refreshments',
            'pricing_note' => 'Premium private evening package for up to eight guests. Rates include return transport from covered South and South-East Coast hotels, an exclusive vessel and crew, confirmed dinner menu and non-alcoholic refreshments. Groups above eight, premium menu requests and other pickup areas receive a confirmed quote.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when booking so we can confirm the departure point, evening pickup time and any remote-area supplement before payment.',
            'meta_title' => 'Private Zanzibar Sunset Dinner Cruise | Coast Rider Tour',
            'meta_description' => 'Book a premium private Zanzibar sunset dinner cruise with hotel transport, exclusive vessel, local crew, confirmed dinner and refreshments.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Private vessel reserved exclusively for your group',
            'Experienced local captain, crew and onboard service',
            'Dinner according to the menu confirmed before payment',
            'Seasonal fruit, drinking water and confirmed soft drinks',
            'Life jackets and standard onboard safety equipment',
            'Applicable activity fees, taxes and service charges',
        ];
        $excluded = [
            'Alcoholic drinks unless listed in the confirmation',
            'Premium seafood, celebration cake, decorations or photography unless added',
            'Guaranteed sunset visibility or a fixed sailing route',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is dinner included?'],
            ['answer' => 'Yes. Dinner is included according to the menu confirmed before payment. Share allergies and dietary requirements when requesting the cruise.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is the vessel exclusive to our group?'],
            ['answer' => 'Yes. The published package includes a vessel reserved exclusively for your confirmed group. The exact vessel is stated in your confirmation.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Can we add celebration extras?'],
            ['answer' => 'Yes. Cake, decorations, flowers, premium drinks or photography can be requested and priced separately before confirmation.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'private-sunset-dinner-cruise')->update([
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

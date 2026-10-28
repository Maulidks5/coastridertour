<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'design-your-day-private-tour')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Design Your Day Private Zanzibar Tour',
            'excerpt' => 'Create a practical full-day private Zanzibar itinerary for up to six guests with one clear group price and confirmed inclusions.',
            'description' => "Tell us what your group would most like to experience and our team will create a practical private itinerary around your hotel, available time, opening hours and travel distances. Suitable cultural, village, nature and scenic stops can be combined when they fit comfortably into one day.\n\nThe USD 239 base rate covers one private group of up to six guests. Before payment, we confirm the route, transport, guide and included entrance fees in writing. Premium boat trips, meals and high-cost special activities require an adjusted quotation.",
            'duration' => 'Full Day',
            'location' => 'Zanzibar',
            'price' => 239,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'group_total',
            'private_1_2_price' => 239,
            'private_3_4_price' => 239,
            'private_5_6_price' => 239,
            'private_7_10_price' => null,
            'private_child_supplement' => 0,
            'private_max_online_guests' => 6,
            'large_group_quote' => true,
            'booking_style_label' => 'One private group price · up to 6 guests',
            'pricing_note' => 'USD 239 is one total base price for a private group of one to six guests. It includes private transport from covered South and South-East Coast hotels, a professional guide and entrance fees specifically listed in the confirmed itinerary. Premium activities, meals, other pickup areas and larger groups are quoted separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel and preferred experiences so we can plan a practical route and confirm any location-based adjustment before payment.',
            'meta_title' => 'Design Your Day Private Zanzibar Tour | Coast Rider',
            'meta_description' => 'Design a private full-day Zanzibar tour for up to six guests with one group price, hotel transport, guide and agreed itinerary fees.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'One private group booking for up to six guests',
            'Private return transport from covered pickup areas',
            'Professional local guide for the confirmed itinerary',
            'Route planning and booking coordination',
            'Entrance fees specifically listed in the confirmation',
            'Government taxes and service charges',
        ];
        $excluded = [
            'Premium boat trips, safaris and high-cost special activities unless listed',
            'Lunch, food and drinks unless confirmed in writing',
            'Entrance fees not listed in the confirmed itinerary',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is USD 239 charged per person?'],
            ['answer' => 'No. USD 239 is one total base price for your entire private group of one to six guests.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Can we choose any activity?'],
            ['answer' => 'Share your preferences and we will propose a route that fits one day. Premium boat trips, safaris, meals and high-cost activities may require an adjusted quote.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Can more than six guests join?'],
            ['answer' => 'Yes. Groups above six receive a custom quotation so we can arrange the correct vehicle, guide and activity capacity.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'design-your-day-private-tour')->update([
            'price' => 0,
            'private_pricing_model' => 'quote_only',
            'private_1_2_price' => null,
            'private_3_4_price' => null,
            'private_5_6_price' => null,
            'private_7_10_price' => null,
            'private_child_supplement' => 0,
            'private_max_online_guests' => 10,
            'booking_style_label' => null,
        ]);
    }
};

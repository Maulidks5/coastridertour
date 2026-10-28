<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'custom-tanzania-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 0,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'private_1_person_price' => null,
            'private_2_person_price' => null,
            'private_3_person_price' => null,
            'private_4_plus_person_price' => null,
            'private_child_price' => null,
            'private_infant_price' => null,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Tailor-made private Tanzania safari · personalised quotation',
            'pricing_note' => 'Planning guide only: value-focused safaris commonly begin around USD 250–350 per adult per day, quality private mid-range safaris around USD 350–500 per adult per day, and premium safaris from about USD 600 per adult per day. These ranges usually assume at least two adults sharing a room; they are not a fixed offer. Your final price depends on route, season, group size, number of nights, accommodation, park fees, flights and transfers. We send one itemised itinerary and final quote before payment.',
            'pickup_details' => 'Pickup and drop-off are designed around your international or domestic flights, Zanzibar connection, hotel and chosen route. Every airport, hotel, boat or airstrip transfer included in the final price is listed in your confirmed itinerary.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What daily budget should I plan for a custom Tanzania safari?'],
            ['answer' => 'As a broad planning guide, value-focused safaris often begin around USD 250–350 per adult per day, private mid-range safaris around USD 350–500, and premium safaris from about USD 600. The real total depends on your dates, group, route, accommodation and transport, so these ranges are not a fixed offer.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Why is there no single starting price on this safari?'],
            ['answer' => 'A custom safari has no fixed duration, route or accommodation level. Showing one headline amount could be misleading. We first review your dates, group size, arrival point, preferred parks and comfort level, then send an itemised itinerary with one confirmed total.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'How can I keep a custom safari within my budget?'],
            ['answer' => 'Travel with more people in one private vehicle, consider low or shoulder season, avoid unnecessary domestic flights, choose well-located value or mid-range camps, and prioritise fewer parks rather than rushing through many areas. We recommend the strongest route for the budget you share.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'custom-tanzania-safari')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 0,
            'private_pricing_model' => 'quote_only',
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => null,
            'pricing_note' => 'Request a personalised quote. The final price depends on travel dates, group size, transport, park fees, accommodation and live availability. Nothing is charged until you approve the itinerary.',
            'pickup_details' => 'Pickup and drop-off are designed around your international or domestic flights, hotel and chosen route. Exact details appear in the confirmed itinerary.',
            'updated_at' => now(),
        ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What daily budget should I plan for a custom Tanzania safari?',
            'Why is there no single starting price on this safari?',
            'How can I keep a custom safari within my budget?',
        ])->delete();
    }
};

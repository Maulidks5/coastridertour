<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-machame-route')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 2650,
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
            'booking_style_label' => 'Private 7-day Machame climb · from USD 2,650 per adult',
            'pricing_note' => 'From USD 2,650 per adult. The final proposal depends on departure date and group size. It includes licensed mountain guides and support crew, TANAPA fees stated in the confirmation, six nights of tented mountain accommodation, camping equipment, meals and treated drinking water on the mountain, and transfers between central Moshi and the route gates. Kilimanjaro Airport or Arusha transfers, pre- and post-climb hotels, personal trekking gear and bookings involving children are quoted separately.',
            'pickup_details' => 'Transfers between central Moshi, Machame Gate and the confirmed descent gate are included in the climb package. Kilimanjaro International Airport, Arusha, hotels outside central Moshi and other pickup points are quoted separately before payment.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')
            ->where('tour_id', $tour->id)
            ->where('label', 'Mountain huts or tents according to the route')
            ->update([
                'label' => 'Tents, sleeping mats and shared mountain camping equipment',
                'updated_at' => now(),
            ]);

        DB::table('tour_inclusions')
            ->where('tour_id', $tour->id)
            ->where('label', 'Confirmed airport, hotel and gate transfers')
            ->update([
                'label' => 'Transfers between central Moshi and the route gates',
                'updated_at' => now(),
            ]);

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What does the USD 2,650 starting price include?'],
            ['answer' => 'The starting adult price includes licensed mountain guides and the required support crew, TANAPA fees stated in your confirmation, six nights of tented mountain accommodation, camping equipment, meals and treated drinking water on the mountain, and transfers between central Moshi and the route gates.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Why is the final Machame price confirmed separately?'],
            ['answer' => 'The final amount can change with your departure date, group size, crew and permit requirements, transfer arrangements and any rental equipment. Requests involving children also need an individual suitability review. We confirm all inclusions and the final total before payment.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        $tour = DB::table('tours')->where('slug', 'kilimanjaro-machame-route')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'price' => 0,
            'private_pricing_model' => 'quote_only',
            'private_1_person_price' => null,
            'private_2_person_price' => null,
            'private_3_person_price' => null,
            'private_4_plus_person_price' => null,
            'private_child_price' => null,
            'private_infant_price' => null,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => null,
            'pricing_note' => 'Request a personalised quote based on dates, group size, route, crew, park fees, accommodation and equipment needs.',
            'pickup_details' => 'Transfers are arranged from Kilimanjaro International Airport, Moshi or Arusha as listed in the confirmed itinerary.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')
            ->where('tour_id', $tour->id)
            ->where('label', 'Tents, sleeping mats and shared mountain camping equipment')
            ->update([
                'label' => 'Mountain huts or tents according to the route',
                'updated_at' => now(),
            ]);

        DB::table('tour_inclusions')
            ->where('tour_id', $tour->id)
            ->where('label', 'Transfers between central Moshi and the route gates')
            ->update([
                'label' => 'Confirmed airport, hotel and gate transfers',
                'updated_at' => now(),
            ]);

        DB::table('tour_faqs')->where('tour_id', $tour->id)->whereIn('question', [
            'What does the USD 2,650 starting price include?',
            'Why is the final Machame price confirmed separately?',
        ])->delete();
    }
};

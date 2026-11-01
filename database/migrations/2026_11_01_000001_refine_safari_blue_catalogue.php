<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'safari-blue')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Safari Blue Dhow Adventure',
            'excerpt' => 'Explore Menai Bay by traditional dhow with guided snorkeling, a natural sandbank, tropical fruit and a seafood BBQ lunch.',
            'description' => "Leave your hotel by private transfer for Fumba, where the local crew welcomes you aboard the shared Safari Blue dhow experience. Spend the day exploring the protected waters of Menai Bay with guided snorkeling, swimming and a natural sandbank stop selected around the tide and sea conditions.\n\nThe day normally continues towards Kwale Island for its coastal scenery, lagoon and ancient baobab area, followed by a freshly prepared seafood BBQ lunch, seasonal tropical fruit and refreshments. The exact order of stops can change for safety, weather and tide conditions, while the core Safari Blue experience remains the same.",
            'duration' => 'Full Day with Lunch',
            'location' => 'Fumba & Menai Bay, Zanzibar',
            'price' => 120,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 195,
            'private_2_person_price' => 145,
            'private_3_person_price' => 130,
            'private_4_plus_person_price' => 120,
            'private_child_price' => 60,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'All-inclusive package · private transfer + shared dhow',
            'pricing_note' => 'From USD 120 per person for four or more adults. The package includes private return transfer from covered South and South-East Coast hotels and the shared Safari Blue dhow experience. Other pickup areas, private-boat requests and groups above ten receive a confirmed quote before payment.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when requesting the tour. Stone Town, North, North-East and other pickup areas may require a confirmed transfer supplement.',
            'meta_title' => 'Safari Blue Zanzibar Dhow Tour with Lunch | Coast Rider',
            'meta_description' => 'Plan an all-inclusive Safari Blue dhow day with private hotel transfer, Menai Bay snorkeling, sandbank time, seafood BBQ lunch and local crew.',
            'updated_at' => now(),
        ]);

        $this->replaceItems('tour_highlights', $tour->id, [
            'Traditional dhow journey through protected Menai Bay',
            'Guided snorkeling with equipment provided',
            'Natural sandbank swimming and relaxation',
            'Kwale Island lagoon and ancient baobab area when conditions allow',
            'Fresh seafood BBQ lunch and tropical fruit',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();

        $included = [
            'Private return hotel transfer from covered South and South-East Coast areas',
            'Shared Safari Blue dhow and boat experience with local crew',
            'Professional local guide and snorkeling assistance',
            'Mask, snorkel, fins and life jacket',
            'Seafood BBQ lunch with a confirmed dietary alternative on request',
            'Seasonal tropical fruit, drinking water and soft drinks',
            'Applicable conservation, activity and government fees',
        ];
        $excluded = [
            'Private boat exclusivity unless confirmed in your quote',
            'Transfer supplements outside the covered pickup zone',
            'Alcoholic drinks, personal purchases and optional activities',
            'Travel insurance, tips and gratuities',
        ];

        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert([
                'tour_id' => $tour->id,
                'label' => $label,
                'is_included' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert([
                'tour_id' => $tour->id,
                'label' => $label,
                'is_included' => false,
                'sort_order' => count($included) + $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $itinerary = [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel to the Fumba departure area.'],
            ['Crew welcome and departure', 'Meet the local crew, receive a safety briefing and begin the Menai Bay dhow experience.'],
            ['Snorkeling and sandbank', 'Enjoy guided snorkeling, swimming and a sandbank stop selected according to tide and sea conditions.'],
            ['Kwale Island and lunch', 'Visit the suitable island area and enjoy a seafood BBQ lunch, tropical fruit and refreshments.'],
            ['Return to your hotel', 'Sail back to Fumba and continue by private vehicle to the confirmed hotel.'],
        ];
        foreach ($itinerary as $index => [$title, $description]) {
            DB::table('tour_itinerary_items')->insert([
                'tour_id' => $tour->id,
                'time_label' => null,
                'title' => $title,
                'description' => $description,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $faqs = [
            ['Is the Safari Blue boat private?', 'The published package includes a private hotel transfer and the shared Safari Blue dhow experience. A private boat can be quoted separately.'],
            ['Is lunch included?', 'Yes. A seafood BBQ lunch, seasonal tropical fruit and refreshments are included. Share allergies or dietary requirements before confirmation.'],
            ['Is snorkeling equipment provided?', 'Yes. Standard mask, snorkel, fins and a life jacket are included. Tell us about non-swimmers, children or equipment concerns in advance.'],
            ['What does the starting price mean?', 'The USD 120 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Can children join?', 'Yes, with a responsible adult. Share every child’s age so we can confirm the correct rate and safe arrangements.'],
            ['Can weather or tides change the itinerary?', 'Yes. Stops and their order may change with tide, wind, sea conditions and safety guidance, while the core experience remains the same.'],
            ['What should I bring?', 'Bring swimwear, a towel, reef-safe sun protection, suitable footwear and a waterproof bag for personal items.'],
        ];
        foreach ($faqs as $index => [$question, $answer]) {
            DB::table('tour_faqs')->insert([
                'tour_id' => $tour->id,
                'question' => $question,
                'answer' => $answer,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Editorial catalogue content remains managed through the admin area.
    }

    /** @param array<int, string> $labels */
    private function replaceItems(string $table, int $tourId, array $labels): void
    {
        DB::table($table)->where('tour_id', $tourId)->delete();

        foreach ($labels as $index => $label) {
            DB::table($table)->insert([
                'tour_id' => $tourId,
                'label' => $label,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};

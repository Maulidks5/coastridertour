<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'prison-island-nakupenda')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Prison Island & Nakupenda Sandbank',
            'excerpt' => 'Meet Prison Island’s giant tortoises, then swim, relax and enjoy a seafood BBQ lunch at Nakupenda on a private full-day boat tour.',
            'description' => "Start with a private transfer from your confirmed hotel to the Stone Town coast, then board a private boat for Prison Island. Explore the historic island with a local guide and visit the Aldabra giant tortoise sanctuary before continuing across the water to Nakupenda Sandbank.\n\nAt Nakupenda, enjoy time to swim, relax and use the provided snorkeling equipment when conditions are suitable. Seasonal tropical fruit, refreshments and a freshly prepared seafood BBQ lunch are included. The activity order, sandbank size and time at each stop depend on tides, weather, sea conditions and local opening hours.",
            'duration' => 'Full Day',
            'location' => 'Prison Island & Nakupenda Sandbank',
            'price' => 95,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 200,
            'private_2_person_price' => 130,
            'private_3_person_price' => 110,
            'private_4_plus_person_price' => 95,
            'private_child_price' => 55,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private island combo · transfer + boat + BBQ lunch',
            'pricing_note' => 'From USD 95 per person for four or more adults. The private package includes covered-area return transfer, private boat, guide, Prison Island entrance, applicable marine fees, snorkeling equipment, seafood BBQ lunch and refreshments. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when requesting the tour so the tide-based departure time and any remote-area transfer supplement can be confirmed.',
            'meta_title' => 'Private Prison Island & Nakupenda Tour with Lunch | Coast Rider',
            'meta_description' => 'Visit Prison Island and Nakupenda privately with hotel transfer, boat, guide, entrance fees, snorkeling equipment and seafood BBQ lunch.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Private boat journey from the Stone Town coast',
            'Guided Prison Island history and sanctuary visit',
            'See the island’s Aldabra giant tortoises',
            'Swim and relax at Nakupenda Sandbank',
            'Fresh seafood BBQ lunch, fruit and refreshments',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered pickup areas',
            'Private boat with experienced local crew and guide',
            'Prison Island entrance and applicable marine fees',
            'Guided historic-island and giant-tortoise sanctuary visit',
            'Nakupenda Sandbank swimming and relaxation time',
            'Mask, snorkel, fins and life jacket',
            'Seafood BBQ lunch with a confirmed alternative on request',
            'Seasonal fruit, drinking water, soft drinks, taxes and service charges',
        ], [
            'Alcoholic drinks and special meals not confirmed in advance',
            'Guaranteed snorkeling visibility or marine-life sightings',
            'Remote-area transfer supplements and optional activities',
            'Personal purchases, tips and travel insurance',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel to the Stone Town boat departure area.'],
            ['Private boat to Prison Island', 'Meet the crew, receive a safety briefing and cross to Prison Island when sea conditions permit.'],
            ['Guided island visit', 'Learn about the island’s history and visit the Aldabra giant tortoise sanctuary with your guide.'],
            ['Nakupenda Sandbank', 'Continue to the sandbank for swimming, relaxation and snorkeling when tide and visibility are suitable.'],
            ['BBQ lunch and refreshments', 'Enjoy a freshly prepared seafood meal, seasonal fruit, drinking water and soft drinks.'],
            ['Boat and hotel return', 'Travel back to the Stone Town coast and meet your driver for the private return transfer.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. Return transport and the boat are arranged privately for your confirmed group of up to eight guests.'],
            ['What does the starting price mean?', 'The USD 95 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Are all island and marine fees included?', 'Yes. Prison Island entrance and the applicable marine fees are included in the package price.'],
            ['Is lunch included?', 'Yes. A seafood BBQ lunch, seasonal fruit, drinking water and soft drinks are included. Share dietary needs and allergies before confirmation.'],
            ['Can you provide a non-seafood meal?', 'A suitable alternative can usually be arranged when requested before confirmation. Dietary needs must be shared in advance.'],
            ['Can Nakupenda be visited at any time?', 'No. Sandbank size, access and visit duration depend on tides, weather and sea conditions. The crew selects safe and practical timing.'],
            ['Is snorkeling equipment included?', 'Yes. Standard mask, snorkel, fins and life jacket are included, but visibility and marine-life sightings cannot be guaranteed.'],
            ['What should I bring?', 'Bring swimwear, a towel, sun protection, suitable water footwear and a waterproof bag for personal items.'],
        ], 'question', 'answer');
    }

    public function down(): void
    {
        // Editorial catalogue content remains managed through the admin area.
    }

    /** @param array<int, string> $labels */
    private function replaceLabels(string $table, int $tourId, array $labels): void
    {
        DB::table($table)->where('tour_id', $tourId)->delete();
        foreach ($labels as $index => $label) {
            DB::table($table)->insert(['tour_id' => $tourId, 'label' => $label, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    /**
     * @param  array<int, string>  $included
     * @param  array<int, string>  $excluded
     */
    private function insertInclusions(int $tourId, array $included, array $excluded): void
    {
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tourId, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tourId, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    /** @param array<int, array{0: string, 1: string}> $items */
    private function insertPairs(string $table, int $tourId, array $items, string $firstColumn, string $secondColumn): void
    {
        foreach ($items as $index => [$first, $second]) {
            DB::table($table)->insert([
                'tour_id' => $tourId,
                $firstColumn => $first,
                $secondColumn => $second,
                ...($table === 'tour_itinerary_items' ? ['time_label' => null] : []),
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};

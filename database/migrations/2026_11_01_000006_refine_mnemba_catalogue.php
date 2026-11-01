<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'mnemba-island')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Mnemba Atoll Snorkeling Experience',
            'excerpt' => 'Explore the reef waters around Mnemba by private boat with hotel transfer, snorkeling guide, equipment, fees and refreshments.',
            'description' => "Start with a private transfer from your confirmed hotel to the Matemwe or Muyuni departure area, then board a private local boat for a guided snorkeling journey around the reef waters near Mnemba Island. Swim above coral, look for tropical fish and other marine life, and enjoy seasonal fruit and refreshments with the crew.\n\nMnemba Island itself is privately owned and landing is not permitted. Dolphins may sometimes be seen along the route, but sightings and swimming interactions are never guaranteed. The captain selects suitable snorkeling areas according to wind, tide, visibility and responsible wildlife practices.",
            'duration' => 'Half Day',
            'location' => 'Matemwe & Mnemba Reef, Zanzibar',
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
            'pricing_note' => 'From USD 65 per person for four or more adults. The private package includes covered-area return transfer, private boat, snorkeling guide, equipment, applicable conservation fees and refreshments. Groups above six and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when requesting the tour so pickup time, departure beach and any remote-area supplement can be confirmed.',
            'meta_title' => 'Private Mnemba Atoll Snorkeling Tour | Coast Rider',
            'meta_description' => 'Snorkel near Mnemba privately with hotel transfer, private boat, local guide, equipment, marine fees, fruit and refreshments.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Private boat journey from the Matemwe or Muyuni coast',
            'Guided snorkeling over suitable Mnemba-area reefs',
            'Tropical fish, coral and seasonal marine-life encounters',
            'Standard snorkeling equipment and life jackets provided',
            'Seasonal tropical fruit and refreshments with the crew',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered pickup areas',
            'Private boat with captain and local snorkeling guide',
            'Mask, snorkel, fins and life jacket',
            'Applicable marine conservation and activity fees',
            'Seasonal tropical fruit, drinking water and soft drinks',
            'Government taxes and service charges',
        ], [
            'Landing on privately owned Mnemba Island',
            'Guaranteed dolphin sightings, swimming or interaction',
            'Lunch, seafood barbecue and alcoholic drinks',
            'Remote-area transfer supplements, personal purchases, tips and insurance',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel to the selected north-east coast departure beach.'],
            ['Boat and safety briefing', 'Meet the captain and guide, check equipment and receive guidance before leaving the beach.'],
            ['Mnemba-area snorkeling', 'Visit suitable reef locations chosen according to visibility, tide and sea conditions.'],
            ['Fruit and refreshments', 'Enjoy seasonal tropical fruit and drinks with time to relax before returning to shore.'],
            ['Private hotel return', 'Meet your driver after the boat trip for the return journey to your confirmed hotel.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. The hotel transfer and boat are arranged privately for your confirmed group of up to six guests.'],
            ['What does the starting price mean?', 'The USD 65 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Can we land on Mnemba Island?', 'No. Mnemba Island is privately owned. The experience takes place in the surrounding reef waters.'],
            ['Are dolphins guaranteed?', 'No. Dolphins are wild animals and sightings or swimming interactions cannot be guaranteed. The crew should avoid chasing or surrounding them.'],
            ['Do I need to be an experienced swimmer?', 'Basic water confidence is helpful. Tell us about non-swimmers or children so suitable support can be confirmed before departure.'],
            ['Are equipment and conservation fees included?', 'Yes. Standard snorkeling equipment, life jackets and applicable marine conservation fees are included.'],
            ['Can weather affect the trip?', 'Yes. Wind, tide, visibility and sea conditions may change the departure time, snorkeling location or travel date for safety.'],
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

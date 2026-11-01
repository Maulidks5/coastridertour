<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'nakupenda-beach')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Nakupenda Sandbank Escape',
            'excerpt' => 'Cruise privately to Nakupenda Sandbank for swimming, snorkeling, relaxation and a freshly prepared seafood BBQ lunch.',
            'description' => "Travel privately from your confirmed hotel to the Stone Town waterfront, then board a private local boat for Nakupenda Sandbank. Spend the day swimming, relaxing beneath the shade and snorkeling when visibility and sea conditions are suitable.\n\nThe crew prepares a seafood BBQ lunch with seasonal tropical fruit and refreshments. Nakupenda is a natural tidal sandbank rather than a permanent island, so its size, arrival time and visit duration change with the tide, wind and sea conditions. The safest and most enjoyable schedule is confirmed for the day.",
            'duration' => 'Full Day with Lunch',
            'location' => 'Nakupenda Sandbank, Stone Town Coast',
            'price' => 75,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 150,
            'private_2_person_price' => 100,
            'private_3_person_price' => 85,
            'private_4_plus_person_price' => 75,
            'private_child_price' => 45,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private sandbank package · boat + transfer + BBQ lunch',
            'pricing_note' => 'From USD 75 per person for four or more adults. The private package includes return transport from covered hotel areas, private boat, guide, snorkeling equipment, seafood BBQ lunch, refreshments and applicable fees. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when requesting the tour so the tide-based departure time and any remote-area supplement can be confirmed.',
            'meta_title' => 'Private Nakupenda Sandbank Tour with BBQ Lunch | Coast Rider',
            'meta_description' => 'Enjoy Nakupenda Sandbank privately with hotel transfer, private boat, snorkeling equipment, seafood BBQ lunch, fruit and refreshments.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Private boat journey from the Stone Town waterfront',
            'Swimming and relaxation on a natural tidal sandbank',
            'Snorkeling when visibility and sea conditions are suitable',
            'Fresh seafood BBQ lunch with a dietary alternative on request',
            'Seasonal tropical fruit and Indian Ocean scenery',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered pickup areas',
            'Private return boat journey from the Stone Town waterfront',
            'Professional local guide, captain and boat crew',
            'Seafood BBQ lunch with a confirmed dietary alternative on request',
            'Seasonal tropical fruit, drinking water and soft drinks',
            'Mask, snorkel, fins and life jacket',
            'Applicable marine fees, government taxes and service charges',
        ], [
            'Prison Island visit and entrance fees',
            'Alcoholic drinks and special meals not confirmed in advance',
            'Guaranteed snorkeling visibility or marine-life sightings',
            'Remote-area transfer supplements, personal purchases, tips and insurance',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel to the Stone Town waterfront.'],
            ['Private boat departure', 'Meet the crew, receive a safety briefing and begin the private journey towards Nakupenda Sandbank.'],
            ['Sandbank swimming and snorkeling', 'Swim, relax and use the provided snorkeling equipment when tide, visibility and sea conditions allow.'],
            ['Seafood BBQ lunch', 'Enjoy a freshly prepared lunch, tropical fruit and refreshments served by the local crew.'],
            ['Boat and hotel return', 'Return privately to Stone Town and continue by vehicle to your confirmed hotel.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. The hotel transfer and boat are arranged privately for your confirmed group.'],
            ['What does the starting price mean?', 'The USD 75 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Are lunch and snorkeling equipment included?', 'Yes. A seafood BBQ lunch, fruit, refreshments and standard snorkeling equipment are included.'],
            ['Can dietary requirements be accommodated?', 'Vegetarian and selected non-seafood alternatives can usually be arranged. Tell us about allergies before confirmation.'],
            ['Is Prison Island included?', 'No. This package visits Nakupenda Sandbank only. Choose the Prison Island and Nakupenda combination when you want both experiences.'],
            ['Does the sandbank always look the same?', 'No. Nakupenda changes naturally with the tide. Its visible size, arrival time and visit duration vary each day.'],
            ['Can weather affect the trip?', 'Yes. Wind, tide, rain and sea conditions may change the departure time or require rescheduling for safety.'],
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

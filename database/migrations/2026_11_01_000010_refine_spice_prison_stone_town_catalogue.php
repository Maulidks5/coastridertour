<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'spice-prison-stone-town')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Spice Farm, Prison Island & Stone Town',
            'excerpt' => 'Discover Zanzibar’s spice heritage, Prison Island’s giant tortoises and historic Stone Town in one privately arranged full day.',
            'description' => "Experience three of Zanzibar’s best-known cultural and island highlights in one carefully planned private day. Walk through a working spice farm with a local host, cross from the Stone Town coast by private boat to Prison Island, and explore the historic lanes and landmarks of UNESCO-listed Stone Town with a knowledgeable guide.\n\nThe activity order is selected around your hotel location, tide, boat conditions, traffic and opening hours. The day is intentionally active, so walking time and individual stops may be adjusted to keep the itinerary practical without rushing unsafe boat or road transfers.",
            'duration' => 'Full Day',
            'location' => 'Spice Farms, Prison Island & Stone Town',
            'price' => 81,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 209,
            'private_2_person_price' => 124,
            'private_3_person_price' => 96,
            'private_4_plus_person_price' => 81,
            'private_child_price' => 40,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private three-in-one tour · transfer + boat + guides',
            'pricing_note' => 'From USD 81 per person for four or more adults. The private full-day package includes return transport from covered South and South-East Coast hotels, private Prison Island boat, local guides, spice-farm entry and tasting, Prison Island entrance and selected Stone Town itinerary fees. Lunch is not included. Other pickup areas and groups above eight receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered South and South-East Coast pickup areas. Share your exact hotel when requesting the tour so pickup time, activity order and any transfer adjustment for another area can be confirmed.',
            'is_published' => true,
            'is_featured' => false,
            'is_popular' => false,
            'meta_title' => 'Spice Farm, Prison Island & Stone Town Tour | Coast Rider',
            'meta_description' => 'Explore a Zanzibar spice farm, Prison Island and Stone Town privately with hotel transfer, boat, guides and applicable entrance fees included.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Guided walk through a working Zanzibar spice farm',
            'Seasonal spice, fruit and tropical-plant tasting',
            'Private boat journey to historic Prison Island',
            'Visit the Aldabra giant tortoise sanctuary',
            'Guided heritage walk through UNESCO-listed Stone Town',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered pickup areas',
            'Professional local guides for the confirmed itinerary',
            'Spice-farm entrance and seasonal tasting',
            'Private Prison Island boat with experienced local crew',
            'Prison Island entrance and giant-tortoise sanctuary visit',
            'Guided Stone Town heritage walk and selected itinerary fees',
            'Drinking water, government taxes and service charges',
        ], [
            'Lunch, additional drinks and alcoholic beverages',
            'Optional museums or attractions not listed in the confirmation',
            'Spices, souvenirs and other personal purchases',
            'Transfer supplements outside covered areas, tips and insurance',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and begin the day in the most practical activity order.'],
            ['Guided spice-farm walk', 'See, smell and taste seasonal spices, tropical fruits and useful plants while learning how they grow.'],
            ['Transfer to the Stone Town coast', 'Travel privately to the boat departure area, with timing adjusted around traffic and tide.'],
            ['Private Prison Island visit', 'Cross with the local crew, learn about the island and visit the Aldabra giant tortoise sanctuary.'],
            ['Stone Town heritage walk', 'Explore historic lanes, carved doors, market atmosphere and the selected landmarks included in your plan.'],
            ['Private hotel return', 'Meet your driver after the guided walk for the return journey to your confirmed hotel.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. The road transfers, Prison Island boat and guided itinerary are arranged for your confirmed group of up to eight guests.'],
            ['What does the starting price mean?', 'The USD 81 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Are the boat and entrance fees included?', 'Yes. The private Prison Island boat, spice-farm entry, Prison Island entrance and selected Stone Town itinerary fees are included.'],
            ['Is lunch included?', 'No. Lunch is not included in the standard package. Your guide can allow a practical meal stop at your own expense or an arranged lunch can be quoted in advance.'],
            ['Will the activities always follow the same order?', 'No. The order may change according to hotel location, tide, boat and traffic conditions, opening hours and the most efficient route.'],
            ['Is this a busy day?', 'Yes. It combines three experiences and includes walking, driving and a boat crossing. Stops may be adjusted to keep the day comfortable and practical.'],
            ['Can extra Stone Town museums be added?', 'Yes, subject to opening hours and available time. Any attraction not included in the standard itinerary will be confirmed with its additional fee.'],
            ['What should I bring?', 'Wear comfortable walking shoes and bring sun protection, modest clothing for town, and a small waterproof bag for the boat journey.'],
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

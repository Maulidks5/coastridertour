<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'prison-island')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Prison Island & Giant Tortoises Tour',
            'excerpt' => 'Travel privately from your hotel and cross from Stone Town by private boat to discover Changuu Island and its giant Aldabra tortoises.',
            'description' => "Begin with a private transfer from your confirmed hotel to the Stone Town waterfront, then board a private local boat for the short crossing to Prison Island, also known as Changuu Island. A local guide introduces the island's history before leading you to the sanctuary of giant Aldabra tortoises.\n\nEnjoy time for photographs, coastal views and a relaxed walk around the accessible historic areas. Swimming may be possible when sea, tide and beach conditions are suitable, but snorkeling is not part of the standard package unless added before confirmation.",
            'duration' => 'Half Day',
            'location' => 'Prison Island, Stone Town Coast',
            'price' => 75,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 145,
            'private_2_person_price' => 100,
            'private_3_person_price' => 85,
            'private_4_plus_person_price' => 75,
            'private_child_price' => 40,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private island package · hotel transfer + private boat',
            'pricing_note' => 'From USD 75 per person for four or more adults. The private package includes return transport from covered hotel areas, a private boat, local guide, island entrance and applicable conservation fees. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when requesting the tour so pickup time and any remote-area supplement can be confirmed before payment.',
            'meta_title' => 'Private Prison Island & Giant Tortoises Tour | Coast Rider',
            'meta_description' => 'Visit Prison Island privately with hotel transfer, private boat, local guide, island fees and time with Zanzibar’s giant Aldabra tortoises.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Private boat crossing from the Stone Town waterfront',
            'Meet and photograph giant Aldabra tortoises',
            'Guided introduction to Changuu Island’s history',
            'Relaxed coastal walk and Indian Ocean views',
            'Private hotel transfer from covered pickup areas',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered pickup areas',
            'Private return boat crossing from the Stone Town waterfront',
            'Professional local guide, captain and boat crew',
            'Prison Island entrance and applicable conservation fees',
            'Guided visit to the giant tortoise sanctuary and historic areas',
            'Life jackets, drinking water, government taxes and service charges',
        ], [
            'Meals, snacks and alcoholic drinks',
            'Snorkeling equipment or a dedicated snorkeling activity',
            'Transfer supplements outside the covered pickup zone',
            'Personal purchases, travel insurance, tips and gratuities',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel to the Stone Town waterfront.'],
            ['Private boat crossing', 'Meet the local crew, receive a safety briefing and cross the channel to Prison Island.'],
            ['Island history walk', 'Explore accessible historic areas while your guide explains the island’s changing role through time.'],
            ['Giant tortoise sanctuary', 'Spend time observing and photographing the giant Aldabra tortoises with guidance from the local team.'],
            ['Boat and hotel return', 'Return privately to Stone Town by boat and meet your driver for the confirmed hotel drop-off.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. The hotel transfer, boat crossing and guided island visit are arranged privately for your confirmed group.'],
            ['What does the starting price mean?', 'The USD 75 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Are the boat and island fees included?', 'Yes. The private return boat, Prison Island entrance and applicable conservation fees are included.'],
            ['Is hotel pickup included?', 'Yes, from covered Zanzibar pickup areas. Any supplement for a remote location is confirmed before payment.'],
            ['Can we swim or snorkel?', 'Swimming may be possible when conditions allow. Snorkeling equipment and a dedicated snorkeling activity are not included unless added before confirmation.'],
            ['Can children join?', 'Yes. Children should remain with a responsible adult, especially during the boat crossing and around the tortoises. Share their ages when booking.'],
            ['Can weather affect the tour?', 'Yes. Departure time and the crossing may change because of wind, tide, sea conditions or island opening hours. Safety guidance takes priority.'],
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

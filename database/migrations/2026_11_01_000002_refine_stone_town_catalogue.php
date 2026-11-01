<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'stone-town-tour')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Historic Stone Town Walking Tour',
            'excerpt' => 'Explore UNESCO-listed Stone Town privately with a knowledgeable local guide, covered-area hotel transfer and confirmed heritage fees.',
            'description' => "Discover the history, architecture and everyday character of UNESCO-listed Stone Town on a privately guided walking tour. Follow narrow lanes past carved Zanzibar doors, Darajani Market, the Old Fort and selected landmarks connected to the island's Swahili, Arab, African, Indian and European heritage.\n\nYour guide adapts the pace and route to your group, with time for questions and photographs. The exact landmarks entered depend on opening hours and the confirmed itinerary. Optional attractions such as the Freddie Mercury Museum are included only when stated in your final confirmation.",
            'duration' => 'Half Day',
            'location' => 'Stone Town, Zanzibar',
            'price' => 60,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 150,
            'private_2_person_price' => 90,
            'private_3_person_price' => 70,
            'private_4_plus_person_price' => 60,
            'private_child_price' => 30,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private heritage tour · hotel transfer + local guide',
            'pricing_note' => 'From USD 60 per person for four or more adults. The private package includes return transfer from covered South and South-East Coast hotels, a professional local guide and applicable entrance fees on the confirmed itinerary. Other pickup areas and groups above ten receive a confirmed adjustment before payment.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when requesting the tour. Stone Town, North, North-East and other pickup areas may receive a lower or adjusted transfer rate.',
            'meta_title' => 'Private Historic Stone Town Walking Tour | Coast Rider',
            'meta_description' => 'Explore UNESCO-listed Stone Town privately with hotel transfer, a professional local guide, historic streets, markets and confirmed heritage fees.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Privately guided walk through UNESCO-listed Stone Town',
            'Traditional carved doors and historic architecture',
            'Darajani Market, Old Fort and selected landmarks',
            'Stories of Zanzibar’s multicultural trading heritage',
            'Flexible pace with time for photographs and questions',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered South and South-East Coast areas',
            'Professional local Stone Town guide',
            'Private heritage walk adapted to your group',
            'Darajani Market, Old Fort and selected public landmarks',
            'Applicable entrance fees listed in the confirmed itinerary',
            'Drinking water, government taxes and service charges',
        ];
        $excluded = [
            'Freddie Mercury Museum and attractions not listed in the confirmation',
            'Meals, snacks and alcoholic drinks',
            'Transfer supplements outside the covered pickup zone',
            'Personal purchases, travel insurance, tips and gratuities',
        ];
        $this->insertInclusions($tour->id, $included, $excluded);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $itinerary = [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to Stone Town.'],
            ['Historic streets and architecture', 'Begin the guided walk through narrow lanes, carved doors and selected heritage landmarks.'],
            ['Market and local life', 'Explore the Darajani area and learn how Stone Town’s trading history connects with daily life today.'],
            ['Old Fort and waterfront area', 'Continue through suitable public landmarks, with optional attractions visited only when confirmed.'],
            ['Private hotel return', 'Meet your driver at the agreed point for the return journey to your hotel.'],
        ];
        $this->insertPairs('tour_itinerary_items', $tour->id, $itinerary, 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $faqs = [
            ['Is this a private tour?', 'Yes. The guide and return transfer are arranged privately for your confirmed group.'],
            ['What does the starting price mean?', 'The USD 60 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Which entrance fees are included?', 'Applicable fees for attractions listed in your confirmed itinerary are included. Optional museums or activities not agreed in advance are paid separately.'],
            ['Is the Freddie Mercury Museum included?', 'Not in the standard package. We can add it before confirmation, subject to opening hours and the applicable entrance fee.'],
            ['How much walking is involved?', 'Stone Town is mainly explored on foot over narrow and sometimes uneven streets. Tell us about mobility needs so the route can be adapted where practical.'],
            ['What should I wear?', 'Wear comfortable walking shoes and light, respectful clothing suitable for Zanzibar’s cultural setting. Sun protection is recommended.'],
            ['How long does the tour take?', 'It is a half-day experience. Total time varies with hotel location, traffic, walking pace, opening hours and the confirmed route.'],
        ];
        $this->insertPairs('tour_faqs', $tour->id, $faqs, 'question', 'answer');
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
            DB::table($table)->insert([
                'tour_id' => $tourId,
                'label' => $label,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * @param  array<int, string>  $included
     * @param  array<int, string>  $excluded
     */
    private function insertInclusions(int $tourId, array $included, array $excluded): void
    {
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert([
                'tour_id' => $tourId,
                'label' => $label,
                'is_included' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert([
                'tour_id' => $tourId,
                'label' => $label,
                'is_included' => false,
                'sort_order' => count($included) + $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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

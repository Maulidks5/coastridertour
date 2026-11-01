<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'spice-tour')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Zanzibar Spice Farm Tour',
            'excerpt' => 'Explore a working spice farm privately with covered-area hotel transfer, local guides and seasonal spice and tropical-fruit tasting.',
            'description' => "Discover why Zanzibar is known as the Spice Island during a private guided walk through a working farm. See how cloves, vanilla, cinnamon, cardamom, black pepper, nutmeg, tropical fruits and useful local plants grow while the farm host explains their scents, flavours and traditional uses.\n\nThe tour is interactive and changes naturally with the season, so the plants and fruits available for tasting vary throughout the year. The standard half-day package includes seasonal tasting but not lunch. A Swahili lunch or cooking experience can be added before confirmation.",
            'duration' => 'Half Day',
            'location' => 'Zanzibar Spice Farms',
            'price' => 40,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 135,
            'private_2_person_price' => 75,
            'private_3_person_price' => 50,
            'private_4_plus_person_price' => 40,
            'private_child_price' => 20,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private spice experience · hotel transfer + farm guide',
            'pricing_note' => 'From USD 40 per person for four or more adults. The private package includes return transport from covered South and South-East Coast hotels, farm entrance, local guides and seasonal tasting. Lunch, cooking experiences, other pickup areas and groups above ten are confirmed separately.',
            'pickup_details' => 'Private return pickup is included from covered South and South-East Coast hotels. Share your exact hotel when requesting the tour. Stone Town, North, North-East and other areas may receive a lower or adjusted transfer rate.',
            'meta_title' => 'Private Zanzibar Spice Farm Tour | Coast Rider',
            'meta_description' => 'Visit a Zanzibar spice farm privately with hotel transfer, local guides, entrance fees and seasonal spice, fruit and coconut tasting.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Private guided walk through a working Zanzibar spice farm',
            'See and smell cloves, vanilla, cinnamon and seasonal spices',
            'Discover tropical fruits and useful local plants',
            'Seasonal spice, fruit and coconut tasting when available',
            'Learn about Zanzibar’s spice-trade heritage and local uses',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered South and South-East Coast areas',
            'Professional local guide and spice-farm host',
            'Private guided walk through a working spice farm',
            'Applicable spice-farm entrance and activity fees',
            'Seasonal spice, fruit and coconut tasting when available',
            'Drinking water, government taxes and service charges',
        ], [
            'Swahili lunch, cooking experience and additional drinks',
            'Spices, soaps, oils and other personal purchases',
            'Transfer supplements outside the covered pickup zone',
            'Travel insurance, tips and gratuities',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to the selected spice farm.'],
            ['Farm welcome', 'Meet the local host and receive an introduction to the farm and Zanzibar’s spice history.'],
            ['Guided spice walk', 'See, smell and learn about seasonal spices, fruits and tropical plants as you walk through the farm.'],
            ['Seasonal tasting', 'Taste suitable spice, fruit or coconut products that are naturally available during your visit.'],
            ['Private hotel return', 'After the farm visit, meet your driver for the return journey to your hotel.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. The transfer and farm experience are arranged privately for your confirmed group.'],
            ['What does the starting price mean?', 'The USD 40 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Is lunch included?', 'No. Lunch is not included in the standard half-day package. A Swahili lunch or cooking experience can be added before confirmation.'],
            ['Are tastings and entrance fees included?', 'Yes. Farm entrance and seasonal spice, fruit or coconut tasting are included when available. The selection changes naturally during the year.'],
            ['Do I have to buy spices?', 'No. You may have an opportunity to buy local products, but purchasing is entirely optional.'],
            ['Is the farm suitable for children?', 'Yes. Children can enjoy the interactive walk and tasting with adult supervision. Share their ages when requesting the tour.'],
            ['What should I bring?', 'Wear comfortable closed or sturdy shoes and bring sun protection and insect repellent, especially after rain.'],
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

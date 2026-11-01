<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'jozani-forest')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Jozani Forest & Red Colobus Tour',
            'excerpt' => 'Discover Jozani’s protected forest with private hotel transfer, park entrance and a guided red colobus and mangrove walk.',
            'description' => "Travel privately from your confirmed hotel to Jozani Chwaka Bay National Park, Zanzibar’s best-known forest reserve. Walk with the required local park guide through suitable forest habitat while learning about conservation, native plants and the endemic Zanzibar red colobus monkey.\n\nWhen access and conditions allow, continue to the mangrove boardwalk to see another important part of the park ecosystem. Red colobus monkeys and other animals are free-ranging wildlife, so sightings, proximity and the exact walking route cannot be guaranteed.",
            'duration' => 'Half Day',
            'location' => 'Jozani Chwaka Bay, Zanzibar',
            'price' => 35,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 90,
            'private_2_person_price' => 50,
            'private_3_person_price' => 40,
            'private_4_plus_person_price' => 35,
            'private_child_price' => 20,
            'private_infant_price' => 0,
            'private_max_online_guests' => 10,
            'large_group_quote' => true,
            'booking_style_label' => 'Private nature package · transfer + park-guided walk',
            'pricing_note' => 'From USD 35 per person for four or more adults. The private half-day package includes return transport from covered South and South-East Coast hotels, Jozani park entrance, the required park guide and drinking water. Other pickup areas and groups above ten receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered South and South-East Coast pickup areas. Share your exact hotel when requesting the tour so pickup time and any transfer adjustment for Stone Town, North, North-East or another area can be confirmed.',
            'meta_title' => 'Private Jozani Forest & Red Colobus Tour | Coast Rider',
            'meta_description' => 'Explore Jozani Forest on a private half-day tour with hotel transfer, park entrance, local guide, red colobus habitat and mangrove boardwalk.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Guided walk through Jozani’s protected forest habitat',
            'Look for endemic Zanzibar red colobus monkeys',
            'Learn about native plants, wildlife and conservation',
            'Explore the mangrove boardwalk when access allows',
            'Private return transport from covered hotel areas',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered pickup areas',
            'Jozani Chwaka Bay National Park entrance fee',
            'Required local park guide for the forest visit',
            'Guided forest and red colobus habitat walk',
            'Mangrove boardwalk when open and conditions allow',
            'Drinking water, government taxes and service charges',
        ], [
            'Food, additional drinks and personal purchases',
            'Guaranteed wildlife sightings, proximity or interaction',
            'Transfer supplements outside covered pickup areas',
            'Tips and personal travel insurance',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to Jozani Chwaka Bay National Park.'],
            ['Park arrival and briefing', 'Meet the required local park guide, confirm the suitable route and receive guidance for respectful wildlife viewing.'],
            ['Guided forest walk', 'Explore the forest habitat, learn about native plants and look for red colobus monkeys and other wildlife when present.'],
            ['Mangrove boardwalk', 'Visit the mangrove ecosystem when the boardwalk is open and park conditions make the visit suitable.'],
            ['Private hotel return', 'Rejoin your driver after the guided visit and return to your confirmed hotel.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. Return transport is arranged privately for your confirmed group, while the forest visit follows the park’s guiding rules.'],
            ['What does the starting price mean?', 'The USD 35 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Are the entrance fee and park guide included?', 'Yes. Jozani Chwaka Bay National Park entrance and the required local park guide are included in the package.'],
            ['Are red colobus sightings guaranteed?', 'No. Red colobus monkeys are free-ranging wildlife. They are frequently observed, but sightings and proximity cannot be guaranteed.'],
            ['Is the mangrove boardwalk always included?', 'It is visited when the boardwalk is open and access, timing and park conditions allow. The guide may adjust the route when necessary.'],
            ['What should I wear?', 'Wear comfortable walking shoes, light clothing and sun or rain protection. Insect repellent may also be useful.'],
            ['Is the tour suitable for children?', 'It can suit families whose children are comfortable walking quietly and following the park guide. Share children’s ages when requesting the tour.'],
            ['How long does the experience take?', 'It is a half-day experience. Total time varies with hotel location, traffic, walking pace and conditions inside the park.'],
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

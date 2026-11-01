<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'nungwi-turtles-sunset')->first();

        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Nungwi Turtles & Private Sunset Dhow',
            'excerpt' => 'Visit a confirmed Nungwi turtle sanctuary, enjoy the north coast and finish with a private traditional dhow cruise at sunset.',
            'description' => "Travel privately to Nungwi for a carefully arranged visit to a confirmed turtle sanctuary operating under its current visitor and animal-welfare rules. Learn about the turtles and the venue with a local host, then enjoy agreed free time around Zanzibar’s northern coast before the evening sailing.\n\nBoard a private traditional dhow with a local crew for coastal views, seasonal fruit and refreshments as daylight fades. Permitted turtle interaction, sunset visibility, departure time and the sailing route depend on venue rules, weather and sea conditions and cannot be guaranteed.",
            'duration' => 'Full Day',
            'location' => 'Nungwi, North Zanzibar',
            'price' => 100,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'per_person_tiered',
            'private_1_person_price' => 240,
            'private_2_person_price' => 155,
            'private_3_person_price' => 120,
            'private_4_plus_person_price' => 100,
            'private_child_price' => 50,
            'private_infant_price' => 0,
            'private_max_online_guests' => 8,
            'large_group_quote' => true,
            'booking_style_label' => 'Private north-coast package · sanctuary + sunset dhow',
            'pricing_note' => 'From USD 100 per person for four or more adults. The private package includes covered-area return transfer, confirmed sanctuary entrance, local host support, private sunset dhow, fruit and refreshments. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when requesting the tour so the afternoon pickup, sanctuary visit, sunset departure and any remote-area supplement can be confirmed.',
            'meta_title' => 'Nungwi Turtles & Private Sunset Dhow Tour | Coast Rider',
            'meta_description' => 'Explore Nungwi with private hotel transfer, a confirmed turtle sanctuary visit and a private traditional dhow cruise with sunset refreshments.',
            'updated_at' => now(),
        ]);

        $this->replaceLabels('tour_highlights', $tour->id, [
            'Visit a confirmed Nungwi turtle sanctuary',
            'Learn about turtles with a local sanctuary host',
            'Enjoy agreed free time on Zanzibar’s north coast',
            'Sail privately on a traditional dhow near sunset',
            'Relax with seasonal fruit and refreshments onboard',
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $this->insertInclusions($tour->id, [
            'Private return hotel transfer from covered pickup areas',
            'Entrance to the confirmed Nungwi turtle sanctuary',
            'Local sanctuary host or guide support',
            'Agreed free time around Nungwi’s coast',
            'Private traditional sunset dhow with local boat crew',
            'Life jackets and onboard safety briefing',
            'Seasonal tropical fruit, drinking water and soft drinks',
            'Government taxes and service charges',
        ], [
            'Guaranteed touching, feeding or swimming with turtles',
            'Guaranteed sunset visibility or a fixed sailing route',
            'Lunch, alcoholic drinks and optional beach activities',
            'Remote-area transfer supplements, personal purchases, tips and insurance',
        ]);

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_itinerary_items', $tour->id, [
            ['Private hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to Zanzibar’s northern coast.'],
            ['Turtle sanctuary visit', 'Meet a local host, learn about the turtles and follow the confirmed venue’s current visitor and animal-welfare rules.'],
            ['Nungwi coast free time', 'Enjoy agreed time for the beach, village atmosphere or refreshments at your own expense before the sailing.'],
            ['Dhow briefing and boarding', 'Meet the local crew, receive safety guidance and board the private traditional dhow when conditions permit.'],
            ['Sunset sailing', 'Enjoy coastal views, seasonal fruit and refreshments along the safe route selected by the captain.'],
            ['Private hotel return', 'Return to shore after the cruise and meet your driver for the arranged evening transfer.'],
        ], 'title', 'description');

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        $this->insertPairs('tour_faqs', $tour->id, [
            ['Is this a private tour?', 'Yes. The return transfer and traditional dhow are arranged privately for your confirmed group of up to eight guests.'],
            ['What does the starting price mean?', 'The USD 100 starting rate applies per person when four or more adults travel together from a covered pickup area. Smaller groups use the group-size rates shown in the calculator.'],
            ['Can we swim with or touch the turtles?', 'This cannot be promised. Any feeding, touching or water interaction depends entirely on the confirmed sanctuary’s current rules and animal-welfare guidance.'],
            ['Is the sanctuary entrance included?', 'Yes. Entrance to the sanctuary confirmed for your booking is included in the package price.'],
            ['Is the sunset dhow private?', 'Yes. The published package includes a traditional dhow reserved for your confirmed group, subject to boat availability and safe sea conditions.'],
            ['Is a clear sunset guaranteed?', 'No. The sailing is planned around sunset, but visibility and colours depend on cloud, weather and sea conditions.'],
            ['What happens if the dhow cannot sail?', 'The captain may adjust, postpone or cancel the sailing in unsafe conditions. We will explain the available rescheduling or recoverable-service option.'],
            ['Is lunch included?', 'No. Lunch is not included in the standard package. It can be arranged separately or purchased during the agreed Nungwi free time.'],
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

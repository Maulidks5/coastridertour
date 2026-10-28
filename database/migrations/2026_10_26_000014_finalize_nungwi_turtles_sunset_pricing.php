<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tour = DB::table('tours')->where('slug', 'nungwi-turtles-sunset')->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            'title' => 'Nungwi Turtles & Private Sunset Dhow',
            'excerpt' => 'Visit a confirmed Nungwi turtle sanctuary, enjoy north-coast free time and finish with a private traditional dhow cruise at sunset.',
            'description' => "Travel privately to Zanzibar's north coast and visit a confirmed Nungwi turtle sanctuary under its current visitor and animal-welfare rules. Learn about the turtles with a local host, then enjoy agreed free time around Nungwi before the evening sailing.\n\nBoard a private traditional dhow with your local crew for coastal views, seasonal fruit and refreshments as daylight fades. Turtle interaction, sunset visibility and the dhow route depend on venue rules, weather and sea conditions and are never guaranteed.",
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
            'pricing_note' => 'All-inclusive private package for up to eight guests. Rates include return transport from covered hotel areas, confirmed turtle-sanctuary entrance, guide support, a private sunset dhow, fruit and refreshments. Groups above eight and remote pickup areas receive a confirmed quote.',
            'pickup_details' => 'Private return hotel transfer is included from covered Zanzibar pickup areas. Share your exact hotel when booking so we can confirm the afternoon pickup, sunset schedule and any remote-area supplement before payment.',
            'meta_title' => 'Nungwi Turtles & Private Sunset Dhow Tour | Coast Rider',
            'meta_description' => 'Book a private Nungwi turtle sanctuary and sunset dhow package with hotel transfer, sanctuary entrance, private boat and refreshments.',
            'updated_at' => now(),
        ]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        $included = [
            'Private return hotel transfer from covered pickup areas',
            'Entrance to the confirmed Nungwi turtle sanctuary',
            'Local sanctuary host or guide support',
            'Agreed free time around Nungwi’s coast',
            'Private traditional sunset dhow and local boat crew',
            'Life jackets and onboard safety briefing',
            'Seasonal tropical fruit, drinking water and soft drinks',
            'Government taxes and service charges',
        ];
        $excluded = [
            'Guaranteed touching, feeding or swimming with turtles',
            'Guaranteed sunset visibility or a fixed sailing route',
            'Lunch, alcoholic drinks and optional beach activities',
            'Tips, personal purchases and unconfirmed pickup supplements',
        ];
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Can we swim with or touch the turtles?'],
            ['answer' => 'This is not guaranteed. Any feeding, touching or water interaction depends entirely on the confirmed sanctuary’s current rules and animal-welfare guidance.', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'Is the sunset dhow private?'],
            ['answer' => 'Yes. The published package includes a traditional dhow reserved for your confirmed group, subject to boat availability and safe sea conditions.', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        );
        DB::table('tour_faqs')->updateOrInsert(
            ['tour_id' => $tour->id, 'question' => 'What happens if the dhow cannot sail?'],
            ['answer' => 'The crew may adjust, postpone or cancel the sailing in unsafe conditions. We will explain the available rescheduling or recoverable-service option.', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('tours')->where('slug', 'nungwi-turtles-sunset')->update([
            'price' => 0,
            'private_pricing_model' => 'quote_only',
            'private_1_person_price' => null,
            'private_2_person_price' => null,
            'private_3_person_price' => null,
            'private_4_plus_person_price' => null,
            'private_child_price' => null,
            'private_infant_price' => null,
            'private_max_online_guests' => 10,
            'booking_style_label' => null,
        ]);
    }
};

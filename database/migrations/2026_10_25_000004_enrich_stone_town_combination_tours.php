<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTour('stone-town-prison-island', [
            'title' => 'Stone Town & Prison Island Tour',
            'excerpt' => 'Combine Stone Town’s cultural heritage with a private boat visit to Prison Island and its giant tortoises in one full-day experience.',
            'description' => "Discover two of Zanzibar’s best-known historic experiences in one privately arranged day. Walk through Stone Town with a local guide, exploring its narrow streets, distinctive architecture, market atmosphere and multicultural history.\n\nContinue by boat to Prison Island for a guided visit, where you can learn about the island’s past and see its giant tortoises. The order of activities may change according to weather, sea conditions, opening hours and the most practical pickup route.",
            'duration' => 'Full Day',
            'location' => 'Stone Town & Prison Island',
            'meta_title' => 'Stone Town & Prison Island Private Tour | Coast Rider',
            'meta_description' => 'Book a full-day private Stone Town and Prison Island tour with transport, local guide, boat transfer and applicable entrance fees.',
        ], [
            'Privately guided Stone Town heritage walk',
            'See historic architecture and carved Zanzibar doors',
            'Private boat journey to Prison Island',
            'Visit the island’s giant tortoise sanctuary',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Professional local tour guide',
            'Return boat transport to Prison Island',
            'Applicable entrance fees on the confirmed itinerary',
            'Government taxes and service charges',
        ], [
            'Lunch, food and drinks unless confirmed in writing',
            'Tips and gratuities',
            'Personal purchases',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to Stone Town.'],
            ['Stone Town heritage walk', 'Explore selected historic streets, architecture, market scenes and cultural landmarks with your local guide.'],
            ['Boat departure', 'Meet the boat crew on the Stone Town coast and cross to Prison Island when sea conditions permit.'],
            ['Prison Island visit', 'Learn about the island’s history and visit the giant tortoise area with your guide.'],
            ['Return to Stone Town', 'Travel back by boat and enjoy agreed free time or a refreshment stop at your own expense.'],
            ['Hotel drop-off', 'Meet your driver for the private return transfer to your hotel.'],
        ], [
            ['How long is the combined tour?', 'It is a full-day experience. Exact pickup and return times depend on your hotel, traffic, walking pace and sea conditions.'],
            ['How is the private price calculated?', 'The displayed adult rate is per person and changes with the number of adults: one, two, three, or four and above. A separate child rate is available.'],
            ['Are the boat and entrance fees included?', 'Yes. Return boat transport and applicable entrance fees on the confirmed itinerary are included in the listed rate.'],
            ['Is lunch included?', 'No. Lunch is not included in the standard rate unless it is specifically added and confirmed before payment.'],
            ['Can the activity order change?', 'Yes. The guide may begin with Prison Island or Stone Town depending on weather, sea conditions, opening hours and logistics.'],
            ['What should I bring?', 'Bring comfortable walking shoes, respectful light clothing, sun protection and a waterproof bag for personal items.'],
        ]);

        $this->updateTour('stone-town-spice-tour', [
            'title' => 'Stone Town & Spice Farm Tour',
            'excerpt' => 'Experience Stone Town’s cultural heritage and the aromas of a Zanzibar spice farm during one privately guided full-day tour.',
            'description' => "Combine Zanzibar’s historic capital with the island’s spice-growing heritage in one practical private itinerary. Explore Stone Town’s narrow streets, carved doors, market atmosphere and selected landmarks with a knowledgeable local guide.\n\nAt a spice farm, discover how familiar spices, seasonal fruits and useful tropical plants grow. The route and order may change with opening hours, traffic, seasonal availability and your confirmed pickup location.",
            'duration' => 'Full Day',
            'location' => 'Stone Town & Zanzibar Spice Farms',
            'meta_title' => 'Stone Town & Spice Farm Private Tour | Coast Rider',
            'meta_description' => 'Book a full-day private Stone Town and Zanzibar Spice Farm tour with hotel transport, professional guide and entrance fees included.',
        ], [
            'Privately guided Stone Town heritage walk',
            'Discover Zanzibar’s architecture and cultural history',
            'Interactive guided walk through a spice farm',
            'See and smell seasonal spices and tropical plants',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Professional local tour guide',
            'Applicable Stone Town and spice-farm entrance fees',
            'Seasonal spice or fruit tasting when available',
            'Government taxes and service charges',
        ], [
            'Lunch, food and additional drinks unless confirmed in writing',
            'Spices, soaps and other personal purchases',
            'Tips and gratuities',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and begin the private journey.'],
            ['Stone Town heritage walk', 'Explore selected streets, architecture, market scenes and cultural landmarks with your local guide.'],
            ['Transfer to the spice farm', 'Travel privately from Stone Town to the selected farm, with the activity order adjusted when practical.'],
            ['Guided spice walk', 'See and smell seasonal spices, fruits and tropical plants while learning about their growth and traditional uses.'],
            ['Tasting and free time', 'Taste available seasonal produce and browse local products without any obligation to buy.'],
            ['Hotel drop-off', 'Meet your driver for the return journey to your confirmed hotel.'],
        ], [
            ['How long is this combination tour?', 'It is a full-day private experience. Total time depends on hotel location, traffic, walking pace and the confirmed route.'],
            ['How is the private price calculated?', 'The displayed adult rate is per person and changes with the number of adults: one, two, three, or four and above. A separate child rate is available.'],
            ['Are entrance fees included?', 'Yes. Applicable entrance fees on the confirmed Stone Town and spice-farm itinerary are included in the listed rate.'],
            ['Is lunch included?', 'No. Lunch is not included in the standard rate unless it is specifically added and confirmed before payment.'],
            ['Do we have to buy spices?', 'No. Buying spices or other farm products is optional and is not part of the tour price.'],
            ['Can the tour order change?', 'Yes. Stone Town or the spice farm may come first depending on traffic, opening hours, seasonal conditions and pickup logistics.'],
        ]);
    }

    private function updateTour(string $slug, array $details, array $highlights, array $included, array $excluded, array $itinerary, array $faqs): void
    {
        $tour = DB::table('tours')->where('slug', $slug)->first();
        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([...$details, 'updated_at' => now()]);

        DB::table('tour_highlights')->where('tour_id', $tour->id)->delete();
        foreach ($highlights as $index => $label) {
            DB::table('tour_highlights')->insert(['tour_id' => $tour->id, 'label' => $label, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        foreach ($itinerary as $index => [$title, $description]) {
            DB::table('tour_itinerary_items')->insert(['tour_id' => $tour->id, 'time_label' => null, 'title' => $title, 'description' => $description, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        foreach ($faqs as $index => [$question, $answer]) {
            DB::table('tour_faqs')->insert(['tour_id' => $tour->id, 'question' => $question, 'answer' => $answer, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Content is editorial data and may be changed later from the admin area.
    }
};

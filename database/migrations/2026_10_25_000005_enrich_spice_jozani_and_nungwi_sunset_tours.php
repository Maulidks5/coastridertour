<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTour('spice-jozani-forest', [
            'title' => 'Spice Farm & Jozani Forest Tour',
            'excerpt' => 'Combine Zanzibar’s spice-growing heritage with a guided walk through Jozani Forest and its red colobus habitat.',
            'description' => "Experience two sides of Zanzibar’s natural heritage in one privately arranged day. Walk through a spice farm with a local guide to discover seasonal spices, tropical fruits and useful plants, then explore the protected habitats of Jozani Chwaka Bay National Park.\n\nAt Jozani, learn about forest conservation and look for free-ranging red colobus monkeys from a respectful distance. The activity order and exact forest route may change with traffic, seasonal conditions, park access and wildlife movement.",
            'duration' => 'Full Day',
            'location' => 'Zanzibar Spice Farms & Jozani Forest',
            'meta_title' => 'Spice Farm & Jozani Forest Private Tour | Coast Rider',
            'meta_description' => 'Book a full-day private Spice Farm and Jozani Forest tour with hotel transport, professional guide and applicable entrance fees.',
        ], [
            'Guided walk through a Zanzibar spice farm',
            'Discover seasonal spices and tropical plants',
            'Explore Jozani’s protected forest habitat',
            'Look for endemic Zanzibar red colobus monkeys',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Professional local tour guide',
            'Spice-farm and Jozani National Park entrance fees',
            'Seasonal spice or fruit tasting when available',
            'Government taxes and service charges',
        ], [
            'Lunch, food and additional drinks unless confirmed in writing',
            'Spices, soaps and other personal purchases',
            'Tips and gratuities',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and begin the private journey.'],
            ['Guided spice walk', 'See and smell seasonal spices, fruits and useful tropical plants while learning how they grow.'],
            ['Transfer to Jozani', 'Travel privately to Jozani Chwaka Bay National Park, with the activity order adjusted when practical.'],
            ['Forest nature walk', 'Explore suitable forest trails while learning about native plants, habitats and conservation.'],
            ['Red colobus viewing', 'Observe free-ranging red colobus monkeys from a respectful distance when they are present.'],
            ['Mangrove habitat and return', 'Visit the mangrove boardwalk when conditions allow, then meet your driver for the hotel transfer.'],
        ], [
            ['How long is this combination tour?', 'It is a full-day private experience. Total time depends on your hotel, traffic, walking pace and local conditions.'],
            ['How is the private price calculated?', 'The displayed adult rate is per person and changes with the number of adults: one, two, three, or four and above. A separate child rate is available.'],
            ['Are all entrance fees included?', 'Yes. The applicable spice-farm and Jozani National Park entrance fees are included in the listed rate.'],
            ['Are monkey sightings guaranteed?', 'No. Red colobus monkeys are free-ranging wildlife. They are commonly seen, but sightings cannot be guaranteed.'],
            ['Is lunch included?', 'No. Lunch is not included in the standard rate unless it is specifically added and confirmed before payment.'],
            ['What should I bring?', 'Wear comfortable walking shoes and bring sun protection, insect repellent and drinking water.'],
        ]);

        $this->updateTour('nungwi-turtles-sunset', [
            'title' => 'Nungwi Turtles & Sunset Dhow Experience',
            'excerpt' => 'Discover Zanzibar’s north coast, visit a Nungwi turtle sanctuary and end the day with a traditional sunset dhow journey.',
            'description' => "Spend a private day exploring Nungwi and Zanzibar’s northern coast. Visit a selected turtle sanctuary to learn about the turtles and local conservation practices, then enjoy time around Nungwi before boarding a traditional dhow for sunset views from the water.\n\nThe exact sanctuary, swimming arrangements and dhow departure depend on the confirmed package, local rules, weather and sea conditions. Animal welfare comes first, and no wildlife interaction should be forced or guaranteed.",
            'duration' => 'Full Day',
            'location' => 'Nungwi, North Zanzibar',
            'price' => 0,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'pricing_note' => 'Private quotation based on pickup area, group size, selected turtle sanctuary and dhow arrangement. We confirm the final total and exact inclusions before payment.',
            'meta_title' => 'Nungwi Turtles & Sunset Dhow Tour | Coast Rider',
            'meta_description' => 'Request a private Nungwi turtle sanctuary and sunset dhow experience with hotel transport and arrangements confirmed for your group.',
        ], [
            'Visit a selected Nungwi turtle sanctuary',
            'Learn about turtles and local conservation efforts',
            'Enjoy time on Zanzibar’s northern coast',
            'Sail on a traditional dhow at sunset',
        ], [
            'Private return transport when listed in the confirmed quote',
            'Professional local guide or host',
            'Turtle sanctuary entrance fee when listed in the confirmed quote',
            'Sunset dhow journey and local boat crew',
            'Safety equipment provided by the boat operator',
        ], [
            'Food and drinks unless listed in the confirmed quote',
            'Tips and gratuities',
            'Personal purchases and optional activities',
            'Services not listed in the confirmed quotation',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed pickup point and travel privately to Zanzibar’s north coast.'],
            ['Turtle sanctuary visit', 'Visit the selected sanctuary with a local host and follow its current animal-welfare and visitor rules.'],
            ['Nungwi free time', 'Enjoy agreed time around Nungwi for the beach, village atmosphere or refreshments at your own expense.'],
            ['Sunset dhow briefing', 'Meet the boat crew, receive safety guidance and board the traditional dhow when conditions permit.'],
            ['Sunset sailing', 'Experience the north-coast sunset from the water along the route selected by the crew.'],
            ['Hotel return', 'Return to shore and meet your driver for the arranged evening transfer.'],
        ], [
            ['Why is this tour priced by quotation?', 'The final price depends on your pickup area, group size, selected sanctuary and whether the dhow is shared or private. The complete total is confirmed before payment.'],
            ['Can we swim with or touch the turtles?', 'Rules vary by sanctuary and can change. Any interaction must follow the venue’s current animal-welfare guidance; touching or swimming is not guaranteed.'],
            ['Is the dhow private?', 'A shared or private dhow may be arranged depending on availability and your preference. The selected arrangement will be stated in your quotation.'],
            ['Is sunset guaranteed?', 'The sailing is timed for sunset, but the visibility and colours depend on weather and cloud conditions.'],
            ['What happens in unsafe sea conditions?', 'The boat operator may adjust, postpone or cancel the sailing for safety. We will explain the available alternative or rescheduling option.'],
            ['What should I bring?', 'Bring comfortable clothing, sun protection, suitable footwear and a light layer for the journey after sunset.'],
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

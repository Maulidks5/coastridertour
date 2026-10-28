<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTour('prison-island', [
            'title' => 'Prison Island & Giant Tortoises Tour',
            'excerpt' => 'Travel by boat from Stone Town for a guided visit to Prison Island, its historic sites and famous giant tortoises.',
            'description' => "Take a private boat journey from the Stone Town coast to Prison Island, also known as Changuu Island. Explore the island with a local guide, learn about its layered history and visit the giant tortoise sanctuary.\n\nThe experience is designed as a convenient half-day excursion. Boat timing and the exact visit may change with weather, sea conditions, opening hours and local conservation requirements.",
            'duration' => 'Half Day',
            'location' => 'Prison Island, Stone Town Coast',
            'is_published' => true,
            'is_popular' => true,
            'is_featured' => false,
            'meta_title' => 'Prison Island & Giant Tortoises Tour Zanzibar | Coast Rider',
            'meta_description' => 'Book a private Prison Island tour with hotel transport, boat transfer, local guide and applicable entrance fees included.',
        ], [
            'Private boat journey from the Stone Town coast',
            'Guided visit to historic Prison Island',
            'See the island’s giant tortoises',
            'Coastal views across the Zanzibar Channel',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Return boat transport to Prison Island',
            'Professional local guide and boat crew',
            'Applicable Prison Island entrance fees',
            'Government taxes and service charges',
        ], [
            'Lunch, food and drinks unless confirmed in writing',
            'Tips and gratuities',
            'Personal purchases and optional activities',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to the Stone Town coast.'],
            ['Boat briefing', 'Meet the local crew, receive safety guidance and prepare for the crossing.'],
            ['Prison Island crossing', 'Travel by boat to Changuu Island when sea conditions permit.'],
            ['Guided island visit', 'Learn about the island’s history and visit the giant tortoise sanctuary with your guide.'],
            ['Return transfer', 'Travel back to Stone Town by boat and meet your driver for the hotel journey.'],
        ], [
            ['How is the private price calculated?', 'The displayed adult rate is per person and changes with the number of adults: one, two, three, or four and above. A separate child rate is available.'],
            ['Are the boat and entrance fees included?', 'Yes. Return boat transport and the applicable Prison Island entrance fee are included in the listed rate.'],
            ['Can we touch or feed the tortoises?', 'Visitors must follow the sanctuary’s current animal-welfare rules and the guide’s instructions. Any interaction is controlled by the venue and cannot be guaranteed.'],
            ['Is swimming included?', 'This standard half-day itinerary focuses on the guided island visit. Ask before booking if you want swimming or snorkeling considered.'],
            ['What happens in unsafe sea conditions?', 'The boat operator may adjust, postpone or cancel the crossing for safety. We will explain the available alternative or rescheduling option.'],
            ['What should I bring?', 'Bring sun protection, comfortable footwear and a waterproof bag for phones or valuables.'],
        ]);

        $this->updateTour('nakupenda-beach', [
            'title' => 'Nakupenda Sandbank Escape',
            'excerpt' => 'Escape by boat to Nakupenda Sandbank for swimming, relaxation and an Indian Ocean experience planned around the tide.',
            'description' => "Leave the Stone Town coast by boat and spend time at Nakupenda Sandbank, a seasonal stretch of white sand surrounded by clear ocean water. Swim, relax and enjoy the open coastal scenery while the local crew manages timing around tide and sea conditions.\n\nThe boat arrangement, meal, refreshments and hotel transfers are tailored to your group and written into the final quotation. Sandbank size, access and visit duration naturally change with the tide.",
            'duration' => 'Full Day',
            'location' => 'Nakupenda Sandbank, Stone Town Coast',
            'price' => 0,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'pricing_note' => 'Private quotation based on pickup area, group size, boat arrangement and meal requirements. The final total and complete inclusions are confirmed before payment.',
            'is_published' => true,
            'is_popular' => true,
            'is_featured' => false,
            'meta_title' => 'Nakupenda Sandbank Private Tour Zanzibar | Coast Rider',
            'meta_description' => 'Request a private Nakupenda Sandbank boat trip with swimming, optional meal and hotel transfers arranged for your group.',
        ], [
            'Private boat journey from the Stone Town coast',
            'Swim in clear Indian Ocean water',
            'Relax on the sandbank according to the tide',
            'Meal arrangements tailored to your group',
        ], [
            'Private return transport when listed in the confirmed quote',
            'Boat transport and experienced local crew',
            'Standard boat safety equipment',
            'Sandbank meal and refreshments when listed in the confirmed quote',
            'Applicable activity fees listed in the quotation',
        ], [
            'Tips and gratuities',
            'Alcoholic drinks unless confirmed in writing',
            'Personal purchases',
            'Services not listed in the confirmed quotation',
        ], [
            ['Hotel pickup', 'Travel from your confirmed pickup point to the Stone Town boat departure area.'],
            ['Boat and safety briefing', 'Meet the crew, review the plan and receive the required safety guidance.'],
            ['Sandbank journey', 'Travel to Nakupenda using the route selected for the day’s tide and sea conditions.'],
            ['Swimming and relaxation', 'Enjoy time in the water and on the sandbank while following the crew’s safety guidance.'],
            ['Meal and refreshments', 'Enjoy the food and drinks specifically listed in your confirmed quotation.'],
            ['Return transfer', 'Travel back to Stone Town and meet your driver when hotel transport is included.'],
        ], [
            ['Why is this tour priced by quotation?', 'The total depends on your pickup area, group size, boat arrangement and meal requirements. You receive the complete price before payment.'],
            ['Is a meal included?', 'A sandbank meal can be included. The menu, refreshments and dietary requirements will be stated clearly in your final quotation.'],
            ['Is the sandbank always fully visible?', 'No. Its size and accessibility change with the tide, and the crew plans the visit around safe practical conditions.'],
            ['Is snorkeling equipment included?', 'Availability depends on the selected boat package. Request snorkeling in advance so equipment can be confirmed in writing.'],
            ['What happens in bad weather?', 'The operator may adjust, postpone or cancel the trip for safety. We will explain the available alternative or rescheduling option.'],
            ['What should I bring?', 'Bring swimwear, a towel, sun protection, suitable water footwear and a waterproof bag for valuables.'],
        ]);

        $this->updateTour('nungwi-aquarium', [
            'title' => 'Nungwi Turtle Sanctuary Experience',
            'excerpt' => 'Visit a selected Nungwi turtle sanctuary, learn about its marine life and enjoy a relaxed private trip to Zanzibar’s north coast.',
            'description' => "Travel privately to Nungwi for a guided visit to a selected turtle sanctuary or natural lagoon. Learn about the turtles, the site’s work and the visitor rules in place at the time of your visit, with optional time to enjoy the north-coast atmosphere.\n\nThe exact venue and permitted interaction vary, so touching or swimming with turtles is never promised. We prioritise current animal-welfare guidance and confirm the sanctuary, entrance fee and transport arrangements before payment.",
            'duration' => 'Half Day',
            'location' => 'Nungwi, North Zanzibar',
            'price' => 0,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'pricing_note' => 'Private quotation based on pickup area, group size and selected turtle sanctuary. The final total, entrance fee and exact inclusions are confirmed before payment.',
            'is_published' => true,
            'is_popular' => true,
            'is_featured' => false,
            'meta_title' => 'Nungwi Turtle Sanctuary Tour Zanzibar | Coast Rider',
            'meta_description' => 'Request a private Nungwi turtle sanctuary visit with hotel transport, entrance arrangements and optional north-coast time.',
        ], [
            'Visit a selected Nungwi turtle sanctuary',
            'Learn about turtles and the site’s visitor rules',
            'Explore Zanzibar’s northern coast privately',
            'Optional Nungwi free time when arranged',
        ], [
            'Private return transport when listed in the confirmed quote',
            'Professional local guide or sanctuary host',
            'Sanctuary entrance fee when listed in the confirmed quote',
            'Government taxes and service charges where applicable',
        ], [
            'Food and drinks unless confirmed in writing',
            'Optional activities and personal purchases',
            'Tips and gratuities',
            'Services not listed in the confirmed quotation',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed pickup point and travel privately to Nungwi.'],
            ['Sanctuary introduction', 'Meet the local host and receive guidance on the venue, turtles and current visitor rules.'],
            ['Guided turtle experience', 'Observe the turtles and take part only in interactions currently permitted by the sanctuary.'],
            ['Optional Nungwi time', 'Add agreed time for the beach, village atmosphere or refreshments when included in your plan.'],
            ['Hotel return', 'Meet your driver for the private return journey to your hotel.'],
        ], [
            ['Why is this tour priced by quotation?', 'The final price depends on your pickup area, group size and selected sanctuary. We confirm the complete total before payment.'],
            ['Can we swim with or touch turtles?', 'Rules vary by venue and can change. Any interaction must follow current animal-welfare guidance; swimming or touching is not guaranteed.'],
            ['Is the entrance fee included?', 'It will be included when listed in your final quotation. We confirm the selected sanctuary and entrance arrangement before payment.'],
            ['Can we add beach time?', 'Yes. Share your preferred timing and we can include practical free time in Nungwi or prepare a longer custom itinerary.'],
            ['Is this suitable for children?', 'Families can join, but children must stay with a responsible adult and follow all sanctuary instructions.'],
            ['What should I bring?', 'Wear comfortable light clothing and bring sun protection, drinking water and swimwear only if your confirmed plan includes permitted swimming.'],
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $commonExcluded = [
            'International flights, visas and travel insurance',
            'Tips, personal purchases and optional activities',
            'Drinks or meals not listed in the confirmed itinerary',
            'Changes requested after confirmation and costs caused by travel disruption',
        ];

        $safaris = [
            'mikumi-day-safari' => [
                'details' => [
                    'title' => 'Mikumi Day Safari from Zanzibar or Dar es Salaam',
                    'excerpt' => 'Fly or drive to Mikumi for a focused full-day game drive across open plains known for elephants, giraffes, zebras and lions.',
                    'description' => "Turn one free day into a genuine mainland wildlife experience. This private Mikumi safari can begin with an early flight from Zanzibar or an overland departure from Dar es Salaam, followed by a guided game drive in a 4x4 safari vehicle.\n\nYour route follows current wildlife movement, park conditions and the confirmed transport schedule. Wildlife is wild and sightings cannot be guaranteed, but your guide uses local knowledge to make the most of the time available.",
                    'duration' => '1 Day',
                    'location' => 'Mikumi National Park',
                    'pickup_details' => 'Pickup is arranged from your confirmed Zanzibar hotel, Zanzibar airport or Dar es Salaam address. Exact timing follows the flight or road itinerary in your final confirmation.',
                    'meta_title' => 'Mikumi Day Safari from Zanzibar | Coast Rider Tour',
                    'meta_description' => 'Plan a private Mikumi day safari from Zanzibar or Dar es Salaam with transfers, a guided 4x4 game drive, park fees and lunch confirmed in one quote.',
                ],
                'highlights' => ['Full-day Mikumi game drive', 'Private 4x4 safari vehicle', 'Elephants, giraffes, zebras and predators', 'Flight or road option arranged around you'],
                'included' => ['Return transfers stated in your confirmed itinerary', 'Domestic transport or flights specifically listed in your quote', 'Private 4x4 game drive with professional driver-guide', 'Mikumi park and conservation fees', 'Lunch and drinking water during the safari'],
                'itinerary' => [
                    ['Early departure', 'Meet your driver for the confirmed airport transfer or begin the road journey from Dar es Salaam.'],
                    ['Arrival and briefing', 'Meet your safari driver-guide, review the day and enter Mikumi National Park.'],
                    ['Morning game drive', 'Explore the plains and water areas while your guide follows recent wildlife activity.'],
                    ['Lunch break', 'Pause for the lunch arrangement specified in your confirmed itinerary.'],
                    ['Afternoon game drive and return', 'Continue the game drive before returning for your flight or road transfer and final drop-off.'],
                ],
                'faqs' => [
                    ['Can I do this safari from Zanzibar in one day?', 'Yes, subject to domestic flight availability and seasonal schedules. We confirm the complete timing before payment.'],
                    ['Are flights included?', 'Flights are included only when they are listed in your final quote. The road option from Dar es Salaam does not require a flight.'],
                    ['Which animals will we see?', 'Mikumi supports elephants, giraffes, zebras, buffalo, antelope, lions and many birds, but no individual sighting is guaranteed.'],
                    ['Is this suitable for children?', 'Yes, although it is a long early-start day. Share each child’s age so we can advise on flights, seats and pace.'],
                ],
            ],
            'mikumi-two-day-safari' => [
                'details' => [
                    'title' => '2-Day Mikumi Safari',
                    'excerpt' => 'Enjoy two unhurried Mikumi game drives and an overnight stay, with more time to follow wildlife than a same-day safari allows.',
                    'description' => "Spend two days exploring Mikumi National Park with a private driver-guide and an overnight stay near the park. The extra time creates a more relaxed pace and gives you both afternoon and morning wildlife-viewing opportunities.\n\nWe build the package around your starting point, preferred accommodation level and available domestic transport. The confirmed quote clearly lists flights or road transfers, lodging, meals and park fees.",
                    'duration' => '2 Days / 1 Night',
                    'location' => 'Mikumi National Park',
                    'pickup_details' => 'Start from Zanzibar or Dar es Salaam. Hotel, airport and park transfers are arranged according to the transport option and accommodation shown in your final itinerary.',
                    'meta_title' => '2-Day Mikumi Safari from Zanzibar | Coast Rider Tour',
                    'meta_description' => 'Request a private two-day Mikumi safari with two game-drive periods, one overnight stay, park fees, meals and transfers clearly itemised.',
                ],
                'highlights' => ['Afternoon and morning game drives', 'One-night safari stay', 'Private vehicle and driver-guide', 'Flexible departure from Zanzibar or Dar'],
                'included' => ['Transfers and domestic transport listed in your quote', 'One night in the confirmed lodge or camp', 'Private 4x4 safari vehicle and professional driver-guide', 'Applicable Mikumi park fees for the confirmed visit', 'Meals and drinking water listed in your itinerary'],
                'itinerary' => [
                    ['Day 1 · Travel and first game drive', 'Travel to Mikumi, meet your guide and begin an afternoon game drive before dinner and overnight at the confirmed property.'],
                    ['Day 2 · Morning game drive and return', 'Explore during the cooler morning hours, stop for the included meal and transfer back according to your flight or road plan.'],
                ],
                'faqs' => [
                    ['Why choose two days instead of one?', 'The overnight plan offers a gentler pace and two separate wildlife-viewing periods, including the cooler morning hours.'],
                    ['What type of accommodation is included?', 'Accommodation depends on your chosen comfort level and availability. The property and room basis appear in the quote before payment.'],
                    ['Are all meals included?', 'Only meals listed in the confirmed itinerary are included. We state the exact meal plan with the accommodation.'],
                    ['Can the safari start in Zanzibar?', 'Yes. We can include domestic flights and Zanzibar transfers, subject to schedule and seat availability.'],
                ],
            ],
            'nyerere-three-day-safari' => [
                'details' => [
                    'title' => '3-Day Nyerere Safari',
                    'excerpt' => 'Discover the quieter southern circuit through 4x4 game drives and, when conditions allow, a Rufiji River boat safari.',
                    'description' => "Explore Nyerere National Park over three days, combining broad wilderness landscapes with guided wildlife viewing. A typical plan includes game drives and a boat experience on the Rufiji River, while the exact sequence depends on arrival time, water level, weather and operator availability.\n\nChoose a camp or lodge that matches your preferred comfort level. Your personalised quote identifies every included transfer, domestic flight, room, meal, park fee and activity before you confirm.",
                    'duration' => '3 Days / 2 Nights',
                    'location' => 'Nyerere National Park',
                    'pickup_details' => 'Zanzibar and Dar es Salaam departures can be arranged. Final pickup times follow confirmed flights, accommodation and activity schedules.',
                    'meta_title' => '3-Day Nyerere Safari from Zanzibar | Coast Rider Tour',
                    'meta_description' => 'Plan a three-day Nyerere safari with game drives, two nights and an optional Rufiji River boat safari, packaged from Zanzibar or Dar es Salaam.',
                ],
                'highlights' => ['Southern-circuit wilderness', 'Private guided game drives', 'Rufiji River scenery', 'Two-night lodge or camp stay'],
                'included' => ['Transfers and domestic flights specified in your quote', 'Two nights at the confirmed camp or lodge', 'Private 4x4 game drives with a professional driver-guide', 'Nyerere park fees and confirmed activities', 'Meals and drinking water shown in the itinerary'],
                'itinerary' => [
                    ['Day 1 · Arrival and first activity', 'Travel to Nyerere, meet your guide and begin the confirmed game drive or river activity before your first overnight stay.'],
                    ['Day 2 · Full safari day', 'Spend the day exploring different habitats by 4x4, with meals and breaks arranged through your camp or lodge.'],
                    ['Day 3 · Final experience and departure', 'Enjoy a final activity when timing permits, then transfer for your return flight or road journey.'],
                ],
                'faqs' => [
                    ['Is a boat safari guaranteed?', 'It is included only when shown in the confirmed itinerary and remains subject to water, weather, safety and operator conditions.'],
                    ['How is Nyerere different from northern parks?', 'Nyerere offers a spacious southern-circuit setting and can combine road game viewing with river scenery.'],
                    ['Can I travel directly from Zanzibar?', 'Yes, when suitable domestic flights operate. We confirm schedules and baggage conditions in your quote.'],
                    ['What accommodation can I choose?', 'We can quote suitable value, comfort or premium options according to your dates and availability.'],
                ],
            ],
            'serengeti-ngorongoro-safari' => [
                'details' => [
                    'title' => 'Serengeti & Ngorongoro Safari',
                    'excerpt' => 'Combine the Serengeti plains with a Ngorongoro Crater game drive in a private 3–5 day journey designed around the season.',
                    'description' => "Experience two of northern Tanzania’s defining wildlife landscapes in one private journey. Search the Serengeti plains with your driver-guide, then descend into Ngorongoro Crater for a game drive among its varied habitats and concentrated wildlife.\n\nThe best route depends on the number of days, travel season, arrival airport and lodge availability. Migration movements and individual wildlife sightings are natural events and cannot be guaranteed. We confirm the park sequence, accommodation and all major costs before payment.",
                    'duration' => '3–5 Days',
                    'location' => 'Serengeti & Ngorongoro',
                    'pickup_details' => 'The itinerary can start from Arusha, Kilimanjaro Airport or an agreed domestic airstrip. Zanzibar connections can be added to the confirmed package.',
                    'meta_title' => 'Serengeti & Ngorongoro Safari 3–5 Days | Coast Rider Tour',
                    'meta_description' => 'Request a private 3–5 day Serengeti and Ngorongoro safari with tailored routing, lodges, guided game drives, park fees and transfers.',
                ],
                'highlights' => ['Serengeti wildlife and open plains', 'Ngorongoro Crater game drive', 'Private 4x4 and driver-guide', 'Seasonally planned 3–5 day route'],
                'included' => ['Private 4x4 safari vehicle and professional driver-guide', 'Accommodation specified in your confirmed itinerary', 'Park, conservation and crater-service fees listed in your quote', 'Meals and drinking water shown in the itinerary', 'Airport or airstrip transfers stated in your package'],
                'itinerary' => [
                    ['Day 1 · Begin the northern circuit', 'Meet your guide and travel toward the Serengeti or connect by domestic flight according to the confirmed route.'],
                    ['Days 2–3 · Serengeti', 'Explore selected Serengeti areas with game drives planned around season, wildlife movement and lodge location.'],
                    ['Day 4 · Ngorongoro highlands', 'Travel toward Ngorongoro with wildlife viewing en route and overnight at the confirmed lodge or camp.'],
                    ['Day 5 · Crater and departure', 'Descend for a crater game drive, then continue to Arusha, the airport or your next confirmed destination. Shorter packages combine these stages.'],
                ],
                'faqs' => [
                    ['Is the Great Migration guaranteed?', 'No. Herd movement changes with rainfall and grazing. We plan the route seasonally but cannot guarantee a particular crossing or location.'],
                    ['Should I choose three, four or five days?', 'Three days is compact; four or five days reduces transfer pressure and allows more game-drive time. We recommend the length after reviewing your flights and priorities.'],
                    ['Are park and crater fees included?', 'Yes when listed in your confirmed quote. Fees can change, so the final proposal shows the applicable costs for your dates.'],
                    ['Can this connect with Zanzibar?', 'Yes. We can add suitable domestic flights and Zanzibar transfers to create one itinerary.'],
                ],
            ],
            'northern-circuit-safari' => [
                'details' => [
                    'title' => 'Northern Circuit Safari: 4–7 Days',
                    'excerpt' => 'Link Tarangire, Lake Manyara, Ngorongoro and the Serengeti in a flexible private safari with time matched to your priorities.',
                    'description' => "See the variety of northern Tanzania on a private route linking elephant country, Rift Valley scenery, Ngorongoro’s volcanic highlands and the Serengeti plains. With four to seven days available, we balance wildlife time against driving distance and your preferred pace.\n\nNot every park needs to be forced into a shorter itinerary. We recommend the strongest route for your season, flights, interests and accommodation budget, then provide one clear quote with the confirmed sequence and inclusions.",
                    'duration' => '4–7 Days',
                    'location' => 'Northern Tanzania',
                    'pickup_details' => 'Start and finish points are tailored around Arusha, Kilimanjaro Airport or domestic flight connections. Zanzibar extensions can be included.',
                    'meta_title' => 'Northern Circuit Safari 4–7 Days Tanzania | Coast Rider Tour',
                    'meta_description' => 'Build a private 4–7 day northern Tanzania safari through Tarangire, Manyara, Ngorongoro and Serengeti with a clear tailored quote.',
                ],
                'highlights' => ['Tarangire elephant country', 'Lake Manyara and Rift Valley scenery', 'Ngorongoro Crater', 'Serengeti plains and flexible routing'],
                'included' => ['Private 4x4 safari vehicle and professional driver-guide', 'Accommodation named in your final itinerary', 'Applicable park, conservation and crater fees', 'Meals and drinking water listed in the package', 'Confirmed airport, hotel and airstrip transfers'],
                'itinerary' => [
                    ['Day 1 · Tarangire', 'Leave Arusha for a Tarangire game drive and overnight at the confirmed lodge or camp.'],
                    ['Day 2 · Lake Manyara or additional Tarangire', 'Explore the selected park according to season and the route agreed before travel.'],
                    ['Day 3 · Ngorongoro highlands', 'Travel through the northern highlands with an optional cultural or scenic stop when included.'],
                    ['Day 4 · Ngorongoro Crater', 'Descend into the crater for a guided game drive before continuing according to your package.'],
                    ['Days 5–7 · Serengeti and departure', 'Longer itineraries add Serengeti game-drive days and a return drive or domestic flight. Four-day routes use a more compact park combination.'],
                ],
                'faqs' => [
                    ['Can all four parks fit into four days?', 'It is possible only with a fast pace and considerable travel. We usually recommend prioritising fewer areas or adding days for a better safari experience.'],
                    ['Which duration is best?', 'Six or seven days provides the best balance for all four areas. Four or five days works well when the route is carefully prioritised.'],
                    ['Can I choose my accommodation level?', 'Yes. We quote available value, comfort or premium stays and state the room and meal basis clearly.'],
                    ['Can Zanzibar be added?', 'Yes. Domestic connections and Zanzibar transfers can be included in the final itinerary.'],
                ],
            ],
            'custom-tanzania-safari' => [
                'details' => [
                    'title' => 'Custom Tanzania Safari',
                    'excerpt' => 'Choose your parks, travel dates, pace and comfort level, and receive one clear safari itinerary built for your group.',
                    'description' => "Create a Tanzania safari around what matters to you instead of adapting to a fixed package. Tell us your dates, group size, arrival point, preferred parks, accommodation style and special interests such as photography, family travel or a Zanzibar extension.\n\nWe respond with a practical route and itemised proposal. Your booking is confirmed only after you approve the final itinerary, accommodation, transport, inclusions and price. Wildlife sightings, migration locations and weather remain outside any operator’s control.",
                    'duration' => 'Your Choice',
                    'location' => 'Tanzania',
                    'pickup_details' => 'Pickup and drop-off are designed around your international or domestic flights, hotel and chosen route. Exact details appear in the confirmed itinerary.',
                    'meta_title' => 'Custom Tanzania Safari Itinerary | Coast Rider Tour',
                    'meta_description' => 'Request a tailor-made Tanzania safari based on your dates, parks, group, comfort level and Zanzibar connections, with one clear final quote.',
                ],
                'highlights' => ['Route designed for your group', 'Choice of parks and duration', 'Value, comfort or premium stays', 'Zanzibar and airport connections'],
                'included' => ['Personal itinerary planning and one clear confirmed quote', 'Private vehicle and professional driver-guide when stated', 'Accommodation, meals and park fees listed in your proposal', 'Airport, hotel, boat or airstrip transfers specifically confirmed', 'Drinking water during included road game drives'],
                'itinerary' => [
                    ['1 · Share your trip', 'Send your dates, number and ages of travellers, arrival point, preferred duration, budget range and must-see experiences.'],
                    ['2 · Receive a practical route', 'We check travel time, availability and seasonal conditions, then recommend suitable parks, transport and accommodation.'],
                    ['3 · Refine the proposal', 'Review the daily itinerary, inclusions and total price and request reasonable changes before confirmation.'],
                    ['4 · Confirm your safari', 'Approve the final plan and payment terms; we then secure the stated services and send your confirmation documents.'],
                ],
                'faqs' => [
                    ['What information should I send?', 'Send travel dates, group size and ages, arrival and departure points, desired safari length, accommodation level and any priority parks or animals.'],
                    ['How quickly will I receive a quote?', 'We aim to respond promptly, but a detailed proposal can take longer when flights or several lodges must be checked.'],
                    ['Can you combine safari and Zanzibar?', 'Yes. We can connect a mainland safari with Zanzibar flights, transfers and island experiences.'],
                    ['Is the first displayed amount the final price?', 'No fixed amount applies to a custom safari. The final written quote depends on your dates, party, route, accommodation and live availability.'],
                ],
            ],
        ];

        foreach ($safaris as $slug => $content) {
            $this->updateSafari($slug, $content, $commonExcluded);
        }
    }

    private function updateSafari(string $slug, array $content, array $commonExcluded): void
    {
        $tour = DB::table('tours')->where('slug', $slug)->first();
        if (! $tour) return;

        DB::table('tours')->where('id', $tour->id)->update([
            ...$content['details'],
            'price' => 0,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'large_group_quote' => true,
            'pricing_note' => 'Request a personalised quote. The final price depends on travel dates, group size, transport, park fees, accommodation and live availability. Nothing is charged until you approve the itinerary.',
            'currency' => 'USD',
            'updated_at' => now(),
        ]);

        $this->replaceRows('tour_highlights', $tour->id, $content['highlights'], fn (string $label, int $order) => ['label' => $label, 'sort_order' => $order]);

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        foreach ($content['included'] as $index => $label) {
            DB::table('tour_inclusions')->insert($this->row($tour->id, ['label' => $label, 'is_included' => true, 'sort_order' => $index + 1]));
        }
        foreach ($commonExcluded as $index => $label) {
            DB::table('tour_inclusions')->insert($this->row($tour->id, ['label' => $label, 'is_included' => false, 'sort_order' => count($content['included']) + $index + 1]));
        }

        $this->replaceRows('tour_itinerary_items', $tour->id, $content['itinerary'], fn (array $item, int $order) => ['time_label' => null, 'title' => $item[0], 'description' => $item[1], 'sort_order' => $order]);
        $this->replaceRows('tour_faqs', $tour->id, $content['faqs'], fn (array $item, int $order) => ['question' => $item[0], 'answer' => $item[1], 'sort_order' => $order]);
    }

    private function replaceRows(string $table, int $tourId, array $items, callable $map): void
    {
        DB::table($table)->where('tour_id', $tourId)->delete();
        foreach ($items as $index => $item) {
            DB::table($table)->insert($this->row($tourId, $map($item, $index + 1)));
        }
    }

    private function row(int $tourId, array $values): array
    {
        return ['tour_id' => $tourId, ...$values, 'created_at' => now(), 'updated_at' => now()];
    }

    public function down(): void
    {
        // Editorial content remains manageable from the admin area after deployment.
    }
};

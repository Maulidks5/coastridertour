<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->catalogue() as $slug => $content) {
            $tour = DB::table('tours')->where('slug', $slug)->first();

            if (! $tour) {
                continue;
            }

            DB::table('tours')->where('id', $tour->id)->update([
                ...$content['details'],
                'shared_available' => false,
                'private_available' => true,
                'private_pricing_model' => 'per_person_tiered',
                'private_1_person_price' => $content['rates'][0],
                'private_2_person_price' => $content['rates'][1],
                'private_3_person_price' => $content['rates'][2],
                'private_4_plus_person_price' => $content['rates'][3],
                'private_child_price' => $content['rates'][4],
                'private_infant_price' => 0,
                'private_max_online_guests' => $content['max_guests'],
                'large_group_quote' => true,
                'price' => $content['rates'][3],
                'updated_at' => now(),
            ]);

            $this->replaceLabels('tour_highlights', $tour->id, $content['highlights']);
            $this->replaceInclusions($tour->id, $content['included'], $content['excluded']);
            $this->replacePairs('tour_itinerary_items', $tour->id, $content['itinerary'], 'title', 'description');
            $this->replacePairs('tour_faqs', $tour->id, $content['faqs'], 'question', 'answer');
        }
    }

    public function down(): void
    {
        // Catalogue content and prices remain editable through the admin area.
    }

    private function catalogue(): array
    {
        return [
            'safari-blue' => [
                'max_guests' => 20,
                'rates' => [195, 145, 125, 102, 51],
                'details' => [
                    'title' => 'Safari Blue Dhow Adventure',
                    'excerpt' => 'Sail through Menai Bay, snorkel over coral reefs, visit a natural sandbank and enjoy a seafood BBQ lunch with tropical fruit.',
                    'description' => "Explore Menai Bay on a traditional dhow with an experienced local crew. The day combines guided snorkeling, swimming, a natural sandbank stop and time around Kwale Island when tide and sea conditions allow.\n\nA freshly prepared seafood BBQ lunch and seasonal tropical fruit are included. Hotel transport is not included in the standard rate and can be added after your pickup area is confirmed.",
                    'booking_style_label' => 'Safari Blue package · dhow + snorkeling + seafood lunch',
                    'pricing_note' => 'From USD 102 per person for four or more adults. The rate includes the dhow trip, snorkeling equipment, guide, seafood BBQ lunch and tropical fruit. Hotel transfer is quoted separately.',
                    'pickup_details' => 'Meet at the confirmed Fumba departure point. Optional return hotel transport can be added after you share your pickup area.',
                    'meta_title' => 'Safari Blue Zanzibar Dhow & Snorkeling Tour | Coast Rider',
                    'meta_description' => 'Book Safari Blue with a Menai Bay dhow trip, snorkeling equipment, sandbank visit, seafood BBQ lunch, tropical fruit and local guide.',
                ],
                'highlights' => ['Snorkel over Menai Bay coral reefs', 'Swim and relax on a natural sandbank', 'Explore Kwale Island and its green lagoon when accessible', 'Enjoy a seafood BBQ lunch and tropical fruit', 'Sail across clear Indian Ocean waters by dhow'],
                'included' => ['Traditional dhow trip with local crew', 'Mask, snorkel, fins and life jacket', 'Seafood BBQ lunch', 'Seasonal tropical fruit', 'Professional local guide'],
                'excluded' => ['Hotel pickup and return transfer', 'Alcoholic drinks', 'Personal expenses, optional activities and tips'],
                'itinerary' => [['Fumba check-in', 'Meet the crew at the confirmed departure point and receive a safety briefing.'], ['Menai Bay sailing', 'Travel by traditional dhow through the protected bay.'], ['Snorkeling and sandbank', 'Snorkel and swim at suitable locations selected around tide and sea conditions.'], ['Kwale Island and lunch', 'Enjoy island time, seafood BBQ and tropical fruit when access permits.'], ['Return to Fumba', 'Sail back to the departure point after the day’s activities.']],
                'faqs' => $this->faqs(102, 'Hotel transport is not included. It can be quoted after your pickup area is known.', 'Yes. Seafood BBQ and tropical fruit are included; share dietary requirements before confirmation.'),
            ],
            'stone-town-tour' => [
                'max_guests' => 15,
                'rates' => [120, 75, 55, 40, 20],
                'details' => [
                    'title' => 'Historic Stone Town Walking Tour',
                    'excerpt' => 'Walk through UNESCO-listed Stone Town with a professional local guide and discover its markets, carved doors and heritage landmarks.',
                    'description' => "Explore Stone Town on foot with a knowledgeable local guide. Follow narrow historic lanes to Darajani Market, carved Swahili doors and selected heritage landmarks connected to Zanzibar’s trading and cultural history.\n\nThe standard rate includes the professional guide and entrance fees for sites listed in the confirmed itinerary. Hotel transport, lunch, shopping and tips are not included.",
                    'booking_style_label' => 'Private heritage walk · guide + selected entrance fees',
                    'pricing_note' => 'From USD 40 per person for four or more adults. The rate includes a professional guide and entrance fees to the sites listed in your confirmed route. Hotel transfer and lunch are excluded.',
                    'pickup_details' => 'Meet your guide at the confirmed Stone Town meeting point. Optional hotel transfer can be added separately.',
                    'meta_title' => 'Historic Stone Town Walking Tour | Coast Rider',
                    'meta_description' => 'Explore Stone Town with a professional local guide, Darajani Market, carved doors and entrance fees to confirmed heritage sites.',
                ],
                'highlights' => ['Explore with a professional licensed local guide', 'See the Freddie Mercury House exterior and House of Wonders area', 'Visit the former slave-market heritage area', 'Experience Darajani Market and local street life', 'Discover iconic carved Swahili and Zanzibari doors', 'Allow time for optional souvenir shopping'],
                'included' => ['Professional local guide', 'Entrance fees to sites listed in the confirmed itinerary'],
                'excluded' => ['Hotel pickup and return transfer', 'Lunch and drinks', 'Personal shopping and tips'],
                'itinerary' => [['Meet your guide', 'Start at the confirmed Stone Town meeting point.'], ['Markets and historic lanes', 'Walk through Darajani and the surrounding old-town streets.'], ['Heritage landmarks', 'Visit the selected cultural and historical sites included in your route.'], ['Carved doors and waterfront', 'Explore architectural details and finish near a practical central location.']],
                'faqs' => $this->faqs(40, 'Hotel transport is not included but can be added separately.', 'Lunch is not included. Your guide can recommend a suitable restaurant after the walk.'),
            ],
            'spice-tour' => [
                'max_guests' => 15,
                'rates' => [135, 90, 70, 60, 30],
                'details' => [
                    'title' => 'Zanzibar Spice Farm Tour with Lunch',
                    'excerpt' => 'Walk through a Zanzibar spice farm, taste seasonal spices and fruit, and enjoy a traditional spiced-rice lunch.',
                    'description' => "Visit a working spice farm with a local guide and discover how cloves, vanilla, cinnamon, nutmeg and other tropical plants grow. Smell and taste seasonal produce while learning about culinary and traditional uses.\n\nThe standard experience includes farm entrance, a local guide, tropical fruit and a traditional spiced-rice lunch. Hotel transport, additional drinks, spice purchases and tips are not included.",
                    'booking_style_label' => 'Spice-farm package · guide + tasting + local lunch',
                    'pricing_note' => 'From USD 60 per person for four or more adults. The rate includes guide, farm entrance, tropical fruit and a traditional spiced-rice lunch. Hotel transfer is quoted separately.',
                    'pickup_details' => 'Meet at the confirmed spice-farm entrance. Optional hotel transport can be arranged after your pickup area is known.',
                    'meta_title' => 'Zanzibar Spice Farm Tour with Local Lunch | Coast Rider',
                    'meta_description' => 'Visit a Zanzibar spice farm with a local guide, entrance, spice tasting, tropical fruit and traditional spiced-rice lunch included.',
                ],
                'highlights' => ['Walk through a working spice plantation', 'See, smell and taste more than ten seasonal spices', 'Enjoy traditional spiced rice and tropical fruit', 'Learn from an experienced local spice guide', 'Shop for farm spices optionally after the visit'],
                'included' => ['Professional local spice guide', 'Spice-farm entrance', 'Traditional spiced-rice lunch', 'Seasonal tropical fruit'],
                'excluded' => ['Hotel pickup and return transfer', 'Additional and alcoholic drinks', 'Spice purchases, personal expenses and tips'],
                'itinerary' => [['Farm arrival', 'Meet the local guide at the confirmed spice-farm entrance.'], ['Guided plantation walk', 'Discover seasonal spices, tropical plants and traditional uses.'], ['Tasting', 'Sample available spices and tropical fruit.'], ['Local lunch', 'Enjoy a traditional spiced-rice meal before concluding the experience.']],
                'faqs' => $this->faqs(60, 'Hotel transport is not included but can be arranged separately.', 'Yes. Traditional spiced rice and seasonal tropical fruit are included; share dietary needs in advance.'),
            ],
            'prison-island' => [
                'max_guests' => 20,
                'rates' => [140, 100, 82, 70, 35],
                'details' => [
                    'title' => 'Prison Island & Giant Tortoises Tour',
                    'excerpt' => 'Cross from Stone Town by boat, learn about Prison Island’s history and visit its Aldabra giant tortoise sanctuary.',
                    'description' => "Travel by boat from the Stone Town coast to Prison Island with a local guide. Learn about the island’s history, visit the giant Aldabra tortoise sanctuary and enjoy available beach or swimming time when conditions allow.\n\nThe standard rate includes return boat transport, island entrance and guide. Hotel transfer, lunch, drinks and tips are not included.",
                    'booking_style_label' => 'Island package · return boat + entrance + guide',
                    'pricing_note' => 'From USD 70 per person for four or more adults. The rate includes return boat transport, Prison Island entrance, guide and sanctuary visit. Hotel transfer is excluded.',
                    'pickup_details' => 'Meet at the confirmed Stone Town boat departure point. Optional hotel transfer can be added separately.',
                    'meta_title' => 'Prison Island & Giant Tortoises Tour | Coast Rider',
                    'meta_description' => 'Visit Prison Island by return boat with entrance fee, local guide, island history and Aldabra giant tortoise sanctuary included.',
                ],
                'highlights' => ['See and photograph Aldabra giant tortoises', 'Learn about Prison Island with a local guide', 'Enjoy swimming or beach time when conditions allow', 'Take a scenic boat ride from the Stone Town coast'],
                'included' => ['Return boat trip', 'Prison Island entrance fee', 'Professional local guide', 'Giant-tortoise sanctuary visit'],
                'excluded' => ['Hotel pickup and return transfer', 'Lunch and drinks', 'Personal expenses and tips'],
                'itinerary' => [['Stone Town departure', 'Meet the crew and receive a boat-safety briefing.'], ['Boat crossing', 'Travel across to Prison Island.'], ['Guided island visit', 'Learn about the history and visit the tortoise sanctuary.'], ['Free time', 'Swim or relax near the beach when local conditions permit.'], ['Return crossing', 'Travel back to the Stone Town coast.']],
                'faqs' => $this->faqs(70, 'Hotel transport is not included but can be quoted separately.', 'Lunch and drinks are not included in the standard tour.'),
            ],
            'nakupenda-beach' => [
                'max_guests' => 20,
                'rates' => [170, 120, 100, 87, 44],
                'details' => [
                    'title' => 'Nakupenda Sandbank Snorkeling Trip',
                    'excerpt' => 'Cruise to Nakupenda Sandbank for snorkeling, swimming, tropical fruit and relaxed time surrounded by clear ocean water.',
                    'description' => "Depart from the Stone Town coast by boat for Nakupenda Sandbank. Use the provided snorkeling equipment, swim when conditions are suitable and relax on the sandbank while enjoying seasonal tropical fruit.\n\nThe standard rate includes return boat transport, snorkeling equipment and fruit. Hotel transfer, lunch, drinks and tips are not included. Sandbank size and timing change with the tide.",
                    'booking_style_label' => 'Sandbank package · return boat + snorkeling + fruit',
                    'pricing_note' => 'From USD 87 per person for four or more adults. The rate includes return boat transport, snorkeling equipment and tropical fruit. Hotel transfer and lunch are excluded.',
                    'pickup_details' => 'Meet at the confirmed Stone Town boat departure point. Optional hotel transport can be added separately.',
                    'meta_title' => 'Nakupenda Sandbank Snorkeling Trip | Coast Rider',
                    'meta_description' => 'Visit Nakupenda Sandbank with return boat, snorkeling equipment, swimming time and seasonal tropical fruit included.',
                ],
                'highlights' => ['Snorkel around Nakupenda when visibility permits', 'Look for colourful reef and marine life', 'Taste seasonal tropical fruit', 'Swim and relax on the tidal sandbank', 'Use standard snorkeling equipment provided'],
                'included' => ['Return boat trip', 'Mask, snorkel, fins and life jacket', 'Seasonal tropical fruit'],
                'excluded' => ['Hotel pickup and return transfer', 'Lunch and drinks', 'Personal expenses and tips'],
                'itinerary' => [['Stone Town departure', 'Meet the crew at the confirmed boat point.'], ['Sandbank cruise', 'Travel to Nakupenda according to tide and sea conditions.'], ['Snorkeling and swimming', 'Use the equipment provided when visibility and conditions are suitable.'], ['Fruit and relaxation', 'Enjoy tropical fruit and time on the sandbank.'], ['Return crossing', 'Travel back to the Stone Town coast.']],
                'faqs' => $this->faqs(87, 'Hotel transport is not included but can be added separately.', 'Lunch and drinks are not included; only seasonal tropical fruit is provided.'),
            ],
            'mnemba-island' => [
                'max_guests' => 12,
                'rates' => [220, 155, 130, 110, 55],
                'details' => [
                    'title' => 'Mnemba Atoll Snorkeling Tour',
                    'excerpt' => 'Travel by speedboat from Matemwe and snorkel over the coral reefs surrounding Mnemba Atoll with a professional guide.',
                    'description' => "Depart by speedboat from Matemwe Beach for guided snorkeling in the reef waters around Mnemba Atoll. Look for tropical fish, coral and seasonal marine life while following the captain’s safety and conservation guidance.\n\nThe standard rate includes speedboat transport from the departure beach, snorkeling equipment and guide. Hotel transfer to Matemwe, lunch and drinks are not included. Landing on privately owned Mnemba Island is not permitted.",
                    'booking_style_label' => 'Snorkeling package · speedboat + equipment + guide',
                    'pricing_note' => 'From USD 110 per person for four or more adults. The rate includes the speedboat from Matemwe, snorkeling equipment and guide. Hotel transfer, lunch and drinks are excluded.',
                    'pickup_details' => 'Meet at the confirmed Matemwe Beach departure point. Optional hotel transfer can be quoted separately.',
                    'meta_title' => 'Mnemba Atoll Speedboat Snorkeling Tour | Coast Rider',
                    'meta_description' => 'Snorkel around Mnemba Atoll with speedboat transport from Matemwe, equipment and professional guide included.',
                ],
                'highlights' => ['Snorkel around the world-known Mnemba Atoll reefs', 'Look for tropical fish, coral and seasonal turtles', 'Explore a protected marine area responsibly', 'Travel by speedboat from Matemwe Beach', 'Snorkel with a professional local guide'],
                'included' => ['Speedboat transport from Matemwe Beach', 'Mask, snorkel, fins and life jacket', 'Professional snorkeling guide'],
                'excluded' => ['Hotel transfer to and from Matemwe', 'Lunch and drinks', 'Landing on privately owned Mnemba Island', 'Personal expenses and tips'],
                'itinerary' => [['Matemwe check-in', 'Meet the captain and guide at the confirmed beach point.'], ['Safety briefing', 'Check equipment and receive snorkeling guidance.'], ['Speedboat journey', 'Travel towards suitable Mnemba-area reef locations.'], ['Guided snorkeling', 'Explore reefs selected around weather, tide and visibility.'], ['Return to Matemwe', 'Travel back to the departure beach.']],
                'faqs' => $this->faqs(110, 'Hotel transport to Matemwe is not included but can be quoted separately.', 'Lunch and drinks are not included. Landing on Mnemba Island is also not permitted.'),
            ],
            'jozani-forest' => [
                'max_guests' => 15,
                'rates' => [160, 115, 95, 80, 40],
                'details' => [
                    'title' => 'Jozani Forest Walking Tour',
                    'excerpt' => 'Look for endemic red colobus monkeys, explore the mangrove boardwalk and learn about Jozani with an expert nature guide.',
                    'description' => "Travel to Jozani Chwaka Bay National Park for a guided nature walk through Zanzibar’s best-known protected forest. Look for endemic red colobus monkeys and other wildlife, then explore the mangrove ecosystem when the boardwalk is accessible.\n\nThe rate includes hotel transfer, park entrance and an expert guide. Lunch, drinks and tips are not included. Wildlife is free-ranging, so sightings cannot be guaranteed.",
                    'booking_style_label' => 'Jozani package · hotel transfer + park entrance + guide',
                    'pricing_note' => 'From USD 80 per person for four or more adults. The rate includes hotel transfer from covered areas, park entrance and expert guide. Lunch and drinks are excluded.',
                    'pickup_details' => 'Return hotel transport is included from covered Zanzibar areas. Share your exact hotel so timing and any remote-area adjustment can be confirmed.',
                    'meta_title' => 'Jozani Forest Walking Tour & Red Colobus | Coast Rider',
                    'meta_description' => 'Explore Jozani Forest with hotel transfer, park entrance, expert nature guide, red colobus habitat and mangrove boardwalk.',
                ],
                'highlights' => ['Look for endangered Zanzibar red colobus monkeys', 'Watch for blue monkeys, bush babies and chameleons', 'Explore the mangrove boardwalk ecosystem', 'Learn from an expert local nature guide', 'Spot seasonal forest birdlife'],
                'included' => ['Return hotel transfer from covered areas', 'Jozani Chwaka Bay National Park entrance', 'Expert local nature guide'],
                'excluded' => ['Lunch and drinks', 'Guaranteed wildlife sightings or interaction', 'Remote-area transfer adjustments, personal expenses and tips'],
                'itinerary' => [['Hotel pickup', 'Meet your driver at the confirmed hotel.'], ['Park arrival', 'Meet the nature guide and receive conservation guidance.'], ['Forest walk', 'Explore suitable habitat and look for free-ranging wildlife.'], ['Mangrove boardwalk', 'Visit the mangrove ecosystem when open and accessible.'], ['Hotel return', 'Rejoin your driver after the guided visit.']],
                'faqs' => $this->faqs(80, 'Hotel transfer is included from covered areas; remote locations may require an adjustment.', 'Lunch and drinks are not included. Wildlife sightings cannot be guaranteed.'),
            ],
            'nungwi-turtles-sunset' => [
                'max_guests' => 12,
                'rates' => [270, 195, 160, 135, 68],
                'details' => [
                    'title' => 'Nungwi Turtle Aquarium & Sunset Dhow',
                    'excerpt' => 'Visit a Nungwi turtle aquarium, learn about marine conservation and finish with a private traditional dhow cruise near sunset.',
                    'description' => "Travel to Nungwi for a guided turtle-aquarium visit under the venue’s current visitor and animal-welfare rules. Later, board a traditional dhow for coastal views as daylight fades.\n\nThe standard package includes return hotel transfer, aquarium entrance, dhow cruise and guide. Lunch, drinks, tips and personal expenses are not included. Turtle interaction and sunset visibility depend on venue rules and natural conditions.",
                    'booking_style_label' => 'North-coast package · transfer + aquarium + dhow cruise',
                    'pricing_note' => 'From USD 135 per person for four or more adults. The rate includes return hotel transfer, turtle-aquarium entrance, dhow cruise and guide. Lunch and drinks are excluded.',
                    'pickup_details' => 'Return hotel transfer to Nungwi is included from covered areas. Share your exact hotel for the confirmed pickup time and any remote-area adjustment.',
                    'meta_title' => 'Nungwi Turtle Aquarium & Sunset Dhow Tour | Coast Rider',
                    'meta_description' => 'Visit Nungwi with hotel transfer, turtle-aquarium entrance, guide and a traditional sunset dhow cruise included.',
                ],
                'highlights' => ['Visit a Nungwi turtle conservation aquarium', 'Learn about rescued sea turtles and marine conservation', 'Follow current rules for any permitted turtle interaction', 'Sail on a traditional dhow near sunset', 'Enjoy Indian Ocean coastal views'],
                'included' => ['Return hotel transfer to and from Nungwi', 'Entrance to the confirmed turtle aquarium', 'Traditional dhow cruise', 'Professional local guide'],
                'excluded' => ['Lunch and drinks', 'Guaranteed swimming, touching or feeding of turtles', 'Guaranteed sunset visibility', 'Personal expenses and tips'],
                'itinerary' => [['Hotel pickup', 'Travel to Nungwi from the confirmed hotel.'], ['Aquarium visit', 'Meet the local host and follow current animal-welfare and visitor rules.'], ['North-coast time', 'Enjoy agreed free time before the evening sailing.'], ['Dhow cruise', 'Board the traditional dhow when weather and sea conditions permit.'], ['Hotel return', 'Meet your driver after returning to shore.']],
                'faqs' => $this->faqs(135, 'Return hotel transfer is included from covered areas.', 'Lunch and drinks are not included. Turtle interaction and sunset visibility cannot be guaranteed.'),
            ],
            'prison-island-nakupenda' => [
                'max_guests' => 20,
                'rates' => [310, 220, 180, 155, 78],
                'details' => [
                    'title' => 'Prison Island & Nakupenda Sandbank Trip',
                    'excerpt' => 'Meet Prison Island’s giant tortoises, snorkel and swim at Nakupenda, and enjoy a seafood lunch with tropical fruit.',
                    'description' => "Depart by boat from the Stone Town coast for a guided Prison Island visit and its Aldabra giant tortoise sanctuary. Continue to Nakupenda Sandbank for swimming, snorkeling and a freshly prepared seafood lunch with tropical fruit.\n\nThe standard rate includes boat transport, Prison Island entrance, guide, snorkeling equipment, seafood lunch and fruit. Hotel transfer, alcoholic drinks and tips are not included.",
                    'booking_style_label' => 'Island combo · boat + entrance + snorkeling + lunch',
                    'pricing_note' => 'From USD 155 per person for four or more adults. The rate includes boat, Prison Island entrance, guide, snorkeling equipment, seafood lunch and tropical fruit. Hotel transfer is excluded.',
                    'pickup_details' => 'Meet at the confirmed Stone Town boat point. Optional hotel transfer can be quoted separately.',
                    'meta_title' => 'Prison Island & Nakupenda Trip with Lunch | Coast Rider',
                    'meta_description' => 'Visit Prison Island and Nakupenda with boat, entrance, guide, snorkeling equipment, seafood lunch and tropical fruit included.',
                ],
                'highlights' => ['Visit Prison Island’s giant tortoises', 'Swim and snorkel at Nakupenda Sandbank', 'Enjoy freshly prepared seafood lunch', 'Taste seasonal tropical fruit', 'Use standard snorkeling equipment provided'],
                'included' => ['Boat trip for the combined itinerary', 'Prison Island entrance fee', 'Professional local guide', 'Mask, snorkel, fins and life jacket', 'Seafood lunch', 'Seasonal tropical fruit'],
                'excluded' => ['Hotel pickup and return transfer', 'Alcoholic and additional drinks', 'Personal expenses and tips'],
                'itinerary' => [['Stone Town departure', 'Meet the guide and crew at the confirmed boat point.'], ['Prison Island', 'Explore the island and giant-tortoise sanctuary.'], ['Nakupenda Sandbank', 'Continue for swimming and snorkeling when conditions permit.'], ['Seafood lunch', 'Enjoy the included meal and tropical fruit.'], ['Return crossing', 'Travel back to the Stone Town coast.']],
                'faqs' => $this->faqs(155, 'Hotel transport is not included but can be added separately.', 'Yes. Seafood lunch and tropical fruit are included; share dietary needs before confirmation.'),
            ],
            'spice-prison-stone-town' => [
                'max_guests' => 15,
                'rates' => [355, 250, 205, 178, 89],
                'details' => [
                    'title' => 'Spice Farm, Prison Island & Stone Town Tour',
                    'excerpt' => 'Combine a guided spice farm with lunch, Prison Island’s tortoises and a UNESCO Stone Town heritage walk in one full day.',
                    'description' => "Discover three signature Zanzibar experiences in one arranged day. Walk through a spice farm with tasting and spiced-rice lunch, cross by boat to Prison Island and its giant tortoise sanctuary, then explore Stone Town’s UNESCO-listed streets with a local guide.\n\nThe package includes hotel pickup and drop-off, farm entrance and lunch, Prison Island boat and entrance, and Stone Town guide. Drinks, shopping and tips are not included. Activity order depends on hotel location, tide, traffic and opening hours.",
                    'booking_style_label' => 'Three-in-one package · transfer + lunch + boat + guides',
                    'pricing_note' => 'From USD 178 per person for four or more adults. The rate includes hotel transfer from covered areas, spice-farm entrance and lunch, Prison Island boat and entrance, and Stone Town guide.',
                    'pickup_details' => 'Return hotel transport is included from covered Zanzibar areas. Share your exact hotel so pickup time and the most practical activity order can be confirmed.',
                    'meta_title' => 'Spice Farm, Prison Island & Stone Town Tour | Coast Rider',
                    'meta_description' => 'Combine Spice Farm lunch, Prison Island and Stone Town with hotel transfer, boat, entrance fees and local guides included.',
                ],
                'highlights' => ['Walk through a spice farm with tasting', 'Enjoy spiced-rice lunch and tropical fruit', 'Visit Prison Island’s giant tortoises', 'Explore UNESCO-listed Stone Town with a guide', 'See key heritage areas including the market and historic waterfront'],
                'included' => ['Return hotel pickup and drop-off from covered areas', 'Spice-farm entrance and local guide', 'Traditional spiced-rice lunch and tropical fruit', 'Boat transport to Prison Island', 'Prison Island entrance', 'Professional Stone Town guide'],
                'excluded' => ['Additional and alcoholic drinks', 'Personal shopping and optional attractions', 'Remote-area transfer adjustments and tips'],
                'itinerary' => [['Hotel pickup', 'Begin from the confirmed hotel in the most practical activity order.'], ['Spice farm and lunch', 'Explore the plantation, taste seasonal produce and enjoy spiced rice.'], ['Prison Island', 'Cross by boat and visit the giant-tortoise sanctuary.'], ['Stone Town walk', 'Explore the selected UNESCO heritage areas with a local guide.'], ['Hotel return', 'Meet your driver after the final activity.']],
                'faqs' => $this->faqs(178, 'Hotel pickup and drop-off are included from covered areas.', 'Yes. A traditional spiced-rice lunch and tropical fruit are included.'),
            ],
        ];
    }

    private function faqs(int $fromPrice, string $transfer, string $meal): array
    {
        return [
            ['What does the starting price mean?', "The USD {$fromPrice} starting rate applies per person when four or more adults book together. Smaller private groups use the group-size rates in the calculator."],
            ['Is transport included?', $transfer],
            ['Are food and drinks included?', $meal],
            ['Is payment required when I send a request?', 'No. We confirm availability, final inclusions, pickup arrangements and the complete price before payment.'],
            ['Can weather or local conditions change the plan?', 'Yes. The guide or captain may adjust timing, order or individual stops for safety, access, tides, traffic or weather.'],
        ];
    }

    /** @param array<int, string> $labels */
    private function replaceLabels(string $table, int $tourId, array $labels): void
    {
        DB::table($table)->where('tour_id', $tourId)->delete();
        foreach ($labels as $index => $label) {
            DB::table($table)->insert(['tour_id' => $tourId, 'label' => $label, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function replaceInclusions(int $tourId, array $included, array $excluded): void
    {
        DB::table('tour_inclusions')->where('tour_id', $tourId)->delete();
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tourId, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tourId, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function replacePairs(string $table, int $tourId, array $items, string $firstColumn, string $secondColumn): void
    {
        DB::table($table)->where('tour_id', $tourId)->delete();
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

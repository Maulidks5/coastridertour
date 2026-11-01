<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('tour_categories')->where('slug', 'tanzania-safaris')->value('id');

        if (! $categoryId) {
            return;
        }

        DB::table('tours')->whereIn('slug', [
            'mikumi-two-day-safari',
            'northern-circuit-safari',
            'tanzania-safaris',
            'safari-zanzibar-beach-escape',
        ])->update(['is_published' => false, 'is_featured' => false, 'is_popular' => false, 'updated_at' => now()]);

        foreach ($this->safaris() as $position => $safari) {
            $existing = DB::table('tours')->where('slug', $safari['slug'])->first();
            $tourId = $existing?->id;

            $details = [
                'tour_category_id' => $categoryId,
                'experience_type' => 'safari',
                'title' => $safari['title'],
                'excerpt' => $safari['excerpt'],
                'description' => $safari['description'],
                'duration' => $safari['duration'],
                'location' => $safari['location'],
                'price' => $safari['price'],
                'currency' => 'USD',
                'cover_image_path' => $safari['image'],
                'pickup_details' => $safari['pickup'],
                'shared_available' => false,
                'private_available' => true,
                'private_pricing_model' => 'quote_only',
                'private_max_online_guests' => 6,
                'large_group_quote' => true,
                'booking_style_label' => 'Fly-in safari · planning price + confirmed quotation',
                'pricing_note' => $safari['pricing_note'],
                'is_featured' => $safari['featured'],
                'is_popular' => $safari['popular'],
                'is_published' => true,
                'sort_order' => 200 + $position,
                'meta_title' => $safari['meta_title'],
                'meta_description' => $safari['meta_description'],
                'updated_at' => now(),
            ];

            if ($tourId) {
                DB::table('tours')->where('id', $tourId)->update($details);
            } else {
                $tourId = DB::table('tours')->insertGetId([
                    ...$details,
                    'slug' => $safari['slug'],
                    'rating' => 0,
                    'reviews_count' => 0,
                    'created_at' => now(),
                ]);
            }

            $this->replaceLabels('tour_highlights', $tourId, $safari['highlights']);
            $this->replaceInclusions($tourId, $safari['included'], $safari['excluded']);
            $this->replacePairs('tour_itinerary_items', $tourId, $safari['itinerary'], 'title', 'description');
            $this->replacePairs('tour_faqs', $tourId, $this->faqs($safari['price'], $safari['duration']), 'question', 'answer');
        }
    }

    public function down(): void
    {
        // Safari catalogue remains editable through the admin area.
    }

    private function safaris(): array
    {
        $nyerereIncluded = ['Return domestic flights from Zanzibar', 'Zanzibar hotel pickup, airport transfers and return', 'Safari activities listed in the itinerary', 'Park entrance and aircraft landing fees', 'Meals listed in the itinerary', 'Professional English-speaking driver-guide'];
        $nyerereExcluded = ['Travel insurance', 'Tips and gratuities', 'Personal expenses and premium drinks', 'Room or lodge upgrades not stated in the package'];

        return [
            [
                'slug' => 'mikumi-day-safari', 'title' => '1-Day Mikumi Fly-In Safari', 'duration' => '1 Day', 'location' => 'Mikumi National Park', 'price' => 400, 'image' => '/images/tours/mikumi-day-safari/cover.webp', 'featured' => true, 'popular' => true,
                'excerpt' => 'Fly from Zanzibar for a full day of shared game drives, wildlife viewing and bush lunch in Mikumi National Park.',
                'description' => "Begin with early hotel pickup and a domestic flight from Zanzibar to Mikumi. Join a professional guide for morning and afternoon shared game drives across open savannah known for elephants, lions, giraffes, zebras, buffalo and hippos.\n\nBush lunch, park fees, drinking water and same-day return flight are included. Wildlife sightings and flight times depend on natural conditions and the operating airline.",
                'pickup' => 'Early-morning hotel pickup in Zanzibar, airport transfer and evening return are included. Passport details are required for the domestic flights.',
                'pricing_note' => 'Planning price from USD 400 per adult. Final price and availability depend on travel date, flight inventory and confirmed passenger details.',
                'meta_title' => '1-Day Mikumi Safari from Zanzibar | Coast Rider', 'meta_description' => 'Fly from Zanzibar to Mikumi for shared 4x4 game drives, park fees, bush lunch, guide, water and same-day return.',
                'highlights' => ['Return domestic flights from Zanzibar', 'Full-day Mikumi National Park safari', 'Shared guided game drives in a 4x4', 'Elephants, lions, giraffes, zebras and hippos', 'Bush lunch inside the national park', 'Same-day return to Zanzibar'],
                'included' => ['Return Zanzibar–Mikumi domestic flights', 'Zanzibar hotel and airport transfers', 'Shared 4x4 game drives', 'Professional English-speaking safari guide', 'Bush lunch and drinking water', 'Park entrance and landing fees'],
                'excluded' => ['Travel insurance', 'Alcoholic and soft drinks', 'Personal expenses, tips and gratuities'],
                'itinerary' => [['Hotel pickup and flight', 'Transfer to Zanzibar Airport for the early flight to Mikumi.'], ['Morning game drive', 'Meet the guide and begin wildlife viewing in a shared 4x4.'], ['Bush lunch', 'Enjoy the included lunch inside the national park.'], ['Afternoon game drive', 'Continue across suitable wildlife areas before the flight.'], ['Return to Zanzibar', 'Fly back and transfer to your confirmed hotel.']],
            ],
            [
                'slug' => 'nyerere-day-safari', 'title' => '1-Day Nyerere Fly-In Safari', 'duration' => '1 Day', 'location' => 'Nyerere National Park', 'price' => 366, 'image' => '/images/tours/nyerere-three-day-safari/cover.webp', 'featured' => true, 'popular' => true,
                'excerpt' => 'Fly from Zanzibar for open-vehicle game drives, a hot bush lunch and same-day return from Nyerere National Park.',
                'description' => "Fly from Zanzibar to Nyerere National Park for a full day in one of Africa’s largest protected wilderness areas. Explore by open 4WD with an English-speaking driver-guide, pausing for coffee or tea and a hot picnic lunch in the bush.\n\nFlights, Zanzibar transfers, park and landing fees, lunch and the game drive are included. Wildlife sightings cannot be guaranteed.",
                'pickup' => 'Early hotel pickup, Zanzibar Airport transfers and same-day return are included. Full passport details are required before ticketing.',
                'pricing_note' => 'Planning price from USD 366 per adult. Child rates and the final total are confirmed from ages, date and flight availability.',
                'meta_title' => '1-Day Nyerere Safari from Zanzibar | Coast Rider', 'meta_description' => 'Fly from Zanzibar to Nyerere for an open-vehicle game drive, park fees, hot bush lunch, guide and same-day return.',
                'highlights' => ['Return domestic flights from Zanzibar', 'Open-vehicle Nyerere game drive', 'Coffee or tea break in the bush', 'Hot picnic lunch with table and chairs', 'Same-day return to Zanzibar'],
                'included' => $nyerereIncluded,
                'excluded' => ['Accommodation', ...$nyerereExcluded],
                'itinerary' => [['Hotel pickup and flight', 'Transfer to Zanzibar Airport and fly to Nyerere.'], ['Open-vehicle game drive', 'Explore suitable park areas with a professional driver-guide.'], ['Bush break and lunch', 'Enjoy coffee or tea and a hot picnic lunch.'], ['Return to Zanzibar', 'Fly back and transfer to your hotel.']],
            ],
            [
                'slug' => 'nyerere-two-day-one-drive', 'title' => '2-Day Nyerere Safari · One Game Drive', 'duration' => '2 Days / 1 Night', 'location' => 'Nyerere National Park', 'price' => 417, 'image' => '/images/tours/nyerere-three-day-safari/cover.webp', 'featured' => false, 'popular' => false,
                'excerpt' => 'Combine a Nyerere game drive with one full-board night near the park and return flights from Zanzibar.',
                'description' => "Fly from Zanzibar to Nyerere for a guided game drive with a bush break and hot picnic lunch. Spend one night on full board at the confirmed safari lodge, then return to Zanzibar after breakfast on day two.\n\nThe final lodge, room type and flight schedule are reconfirmed before payment.",
                'pickup' => 'Zanzibar hotel pickup, airport transfers and return flights are included. Passport details are required for every traveller.',
                'pricing_note' => 'Planning price from USD 417 per adult sharing. Single-room, third-adult and child rates are confirmed in the written quote.',
                'meta_title' => '2-Day Nyerere Safari with One Game Drive | Coast Rider', 'meta_description' => 'Fly from Zanzibar for one Nyerere game drive, hot bush lunch and one full-board safari-lodge night.',
                'highlights' => ['Return flights from Zanzibar', 'Guided Nyerere game drive', 'Hot picnic lunch in the bush', 'One full-board safari-lodge night', 'Open 4WD safari vehicle'],
                'included' => [...$nyerereIncluded, 'One night full-board accommodation'], 'excluded' => $nyerereExcluded,
                'itinerary' => [['Day 1 · Flight and game drive', 'Fly from Zanzibar, enjoy the guided game drive and hot picnic lunch, then check in for a full-board overnight.'], ['Day 2 · Breakfast and return', 'Check out after breakfast, fly to Zanzibar and transfer to your hotel.']],
            ],
            [
                'slug' => 'nyerere-two-day-two-drives', 'title' => '2-Day Nyerere Safari · Two Game Drives', 'duration' => '2 Days / 1 Night', 'location' => 'Nyerere National Park', 'price' => 522, 'image' => '/images/tours/nyerere-three-day-safari/cover.webp', 'featured' => false, 'popular' => false,
                'excerpt' => 'Spend two days exploring Nyerere with two guided game drives, full-board accommodation and flights from Zanzibar.',
                'description' => "Fly from Zanzibar for two guided game-drive sessions in Nyerere National Park. Enjoy hot picnic lunches, an overnight full-board stay and more time searching for wildlife before returning to Zanzibar on day two.\n\nThe programme follows flight schedules, park conditions and the final lodge confirmation.",
                'pickup' => 'Zanzibar hotel pickup, airport transfers and return flights are included. Passport details are required for ticketing.',
                'pricing_note' => 'Planning price from USD 522 per adult sharing. Single-room, third-adult and child rates are confirmed in writing.',
                'meta_title' => '2-Day Nyerere Safari with Two Game Drives | Coast Rider', 'meta_description' => 'Fly from Zanzibar for two Nyerere game drives, hot picnic lunches and one full-board safari-lodge night.',
                'highlights' => ['Return flights from Zanzibar', 'Two guided Nyerere game drives', 'Hot picnic lunches in the bush', 'One full-board safari-lodge night', 'More time for wildlife viewing'],
                'included' => [...$nyerereIncluded, 'One night full-board accommodation', 'Two game drives as listed'], 'excluded' => $nyerereExcluded,
                'itinerary' => [['Day 1 · Flight and first game drive', 'Fly from Zanzibar, begin the safari and stay overnight on full board.'], ['Day 2 · Second game drive and return', 'Enjoy another guided drive and picnic lunch before flying back to Zanzibar.']],
            ],
            [
                'slug' => 'nyerere-three-day-safari', 'title' => '3-Day Nyerere Classic Safari', 'duration' => '3 Days / 2 Nights', 'location' => 'Nyerere National Park', 'price' => 539, 'image' => '/images/tours/nyerere-three-day-safari/cover.webp', 'featured' => true, 'popular' => true,
                'excerpt' => 'Combine a Rufiji River boat safari, guided bushwalk and full-day game drive with two full-board nights.',
                'description' => "Experience Nyerere by foot, boat and safari vehicle. Begin with a guided bushwalk and Rufiji River boat safari, spend the second day on a full game drive, and stay two nights on full board near the park.\n\nReturn flights and Zanzibar hotel transfers are included, with exact activity timing confirmed around flight and park operations.",
                'pickup' => 'Early Zanzibar hotel pickup, airport transfers and return domestic flights are included. Passport details are required.',
                'pricing_note' => 'Planning price from USD 539 per adult sharing. Single-room, third-adult and child rates are confirmed in the final quote.',
                'meta_title' => '3-Day Nyerere Classic Safari from Zanzibar | Coast Rider', 'meta_description' => 'Explore Nyerere with a bushwalk, Rufiji River boat safari, full-day game drive, flights and two full-board nights.',
                'highlights' => ['Return flights from Zanzibar', 'Guided bushwalk', 'Rufiji River boat safari', 'Full-day Nyerere game drive', 'Two full-board safari-lodge nights', 'Hippos, crocodiles and river birdlife'],
                'included' => [...$nyerereIncluded, 'Two nights full-board accommodation', 'Boat safari and guided bushwalk'], 'excluded' => $nyerereExcluded,
                'itinerary' => [['Day 1 · Bushwalk and boat safari', 'Fly to Nyerere, take a guided bushwalk and explore the Rufiji River by boat.'], ['Day 2 · Full-day game drive', 'Spend the day wildlife viewing by safari vehicle.'], ['Day 3 · Breakfast and return', 'Check out, fly to Zanzibar and transfer to your hotel.']],
            ],
            [
                'slug' => 'nyerere-three-day-hot-springs', 'title' => '3-Day Nyerere Hot Springs Safari', 'duration' => '3 Days / 2 Nights', 'location' => 'Nyerere National Park', 'price' => 591, 'image' => '/images/tours/nyerere-three-day-safari/cover.webp', 'featured' => false, 'popular' => false,
                'excerpt' => 'Pair classic Nyerere game drives with a full-day journey towards the remote Beho Beho hot springs.',
                'description' => "Fly from Zanzibar for a first-day game drive and hot picnic lunch, then dedicate day two to a guided game-drive journey towards the Beho Beho hot-springs area. Stay two nights on full board before returning to Zanzibar.\n\nAccess and the exact route depend on park, road and weather conditions.",
                'pickup' => 'Zanzibar hotel transfers and return flights are included. Passport details are required before confirmation.',
                'pricing_note' => 'Planning price from USD 591 per adult sharing. Room configuration and child rates are confirmed separately.',
                'meta_title' => '3-Day Nyerere Hot Springs Safari | Coast Rider', 'meta_description' => 'Combine Nyerere game drives, Beho Beho hot springs, flights and two full-board safari-lodge nights.',
                'highlights' => ['Return flights from Zanzibar', 'Nyerere game drives', 'Beho Beho hot-springs journey', 'Hot picnic bush lunch', 'Two full-board safari-lodge nights'],
                'included' => [...$nyerereIncluded, 'Two nights full-board accommodation', 'Hot-springs itinerary when accessible'], 'excluded' => $nyerereExcluded,
                'itinerary' => [['Day 1 · Flight and game drive', 'Fly to Nyerere and begin the safari with a hot picnic lunch.'], ['Day 2 · Beho Beho hot springs', 'Explore the remote area by guided safari vehicle when access permits.'], ['Day 3 · Breakfast and return', 'Fly back to Zanzibar and transfer to your hotel.']],
            ],
            [
                'slug' => 'nyerere-four-day-extended', 'title' => '4-Day Extended Nyerere Safari', 'duration' => '4 Days / 3 Nights', 'location' => 'Nyerere National Park', 'price' => 712, 'image' => '/images/tours/nyerere-three-day-safari/cover.webp', 'featured' => false, 'popular' => false,
                'excerpt' => 'Explore Nyerere through a bushwalk, Rufiji boat safari, full-day game drive and Beho Beho hot-springs journey.',
                'description' => "Spend four days combining Nyerere’s signature activities: a guided bushwalk, Rufiji River boat safari, full-day game drive and a journey towards the Beho Beho hot springs. Three full-board nights provide a more relaxed pace.\n\nFlights, Zanzibar transfers, park fees, listed meals and professional guiding are included.",
                'pickup' => 'Return Zanzibar hotel and airport transfers are included. Passport details are required for domestic flights.',
                'pricing_note' => 'Planning price from USD 712 per adult sharing. Final room, child and flight rates are confirmed in writing.',
                'meta_title' => '4-Day Extended Nyerere Safari | Coast Rider', 'meta_description' => 'Explore Nyerere for four days with game drives, bushwalk, Rufiji boat safari, hot springs, flights and full board.',
                'highlights' => ['Return flights from Zanzibar', 'Guided bushwalk', 'Rufiji River boat safari', 'Full-day Nyerere game drive', 'Beho Beho hot springs', 'Three full-board nights'],
                'included' => [...$nyerereIncluded, 'Three nights full-board accommodation', 'Boat safari, bushwalk and hot-springs itinerary'], 'excluded' => $nyerereExcluded,
                'itinerary' => [['Day 1 · Bushwalk and boat safari', 'Fly in and explore by foot and boat.'], ['Day 2 · Full-day game drive', 'Spend the day searching for wildlife.'], ['Day 3 · Beho Beho hot springs', 'Travel through the remote park area when accessible.'], ['Day 4 · Breakfast and return', 'Fly to Zanzibar and transfer to your hotel.']],
            ],
            [
                'slug' => 'nyerere-five-day-ultimate', 'title' => '5-Day Ultimate Nyerere Safari', 'duration' => '5 Days / 4 Nights', 'location' => 'Nyerere National Park', 'price' => 866, 'image' => '/images/tours/nyerere-three-day-safari/cover.webp', 'featured' => false, 'popular' => false,
                'excerpt' => 'Spend five immersive days on game drives, a bushwalk, Rufiji River boat safari and Beho Beho hot-springs journeys.',
                'description' => "Take the most immersive Nyerere itinerary with multiple game drives, two journeys towards Beho Beho, a guided bushwalk and a Rufiji River boat safari. Four full-board nights allow time to experience the park at a measured pace.\n\nReturn flights, Zanzibar transfers, park fees, listed meals and professional guiding are included.",
                'pickup' => 'Return Zanzibar hotel and airport transfers are included. Passport details are required for domestic flights.',
                'pricing_note' => 'Planning price from USD 866 per adult sharing. Final room, child and flight rates are confirmed before payment.',
                'meta_title' => '5-Day Ultimate Nyerere Safari | Coast Rider', 'meta_description' => 'Spend five days in Nyerere with game drives, bushwalk, Rufiji boat safari, hot springs, flights and full board.',
                'highlights' => ['Return flights from Zanzibar', 'Multiple full-day game drives', 'Two Beho Beho hot-springs journeys', 'Guided bushwalk', 'Rufiji River boat safari', 'Four full-board nights'],
                'included' => [...$nyerereIncluded, 'Four nights full-board accommodation', 'Boat safari, bushwalk and listed hot-springs journeys'], 'excluded' => $nyerereExcluded,
                'itinerary' => [['Day 1 · Flight and game drive', 'Fly to Nyerere and begin wildlife viewing.'], ['Day 2 · Beho Beho hot springs', 'Explore the remote area when accessible.'], ['Day 3 · Bushwalk and boat safari', 'Experience the park on foot and along the Rufiji River.'], ['Day 4 · Game drive and hot springs', 'Continue through suitable wildlife and hot-springs areas.'], ['Day 5 · Breakfast and return', 'Fly back to Zanzibar and transfer to your hotel.']],
            ],
            [
                'slug' => 'serengeti-ngorongoro-safari', 'title' => '3-Day Tarangire, Serengeti & Ngorongoro Safari', 'duration' => '3 Days / 2 Nights', 'location' => 'Northern Tanzania', 'price' => 1550, 'image' => '/images/tours/serengeti-ngorongoro-safari/cover.webp', 'featured' => true, 'popular' => true,
                'excerpt' => 'Fly from Zanzibar for Tarangire elephants, Serengeti plains and a Ngorongoro Crater game drive in three days.',
                'description' => "Experience Tanzania’s classic northern circuit in three days. Explore Tarangire’s elephant and baobab landscapes, continue to the Serengeti for extensive wildlife viewing, then descend into Ngorongoro Crater before the return journey.\n\nThe package includes return Zanzibar flights, two nights’ accommodation, meals listed in the itinerary, private 4x4 Land Cruiser, professional guide, park fees and crater service fee.",
                'pickup' => 'The safari begins with the confirmed Zanzibar–Arusha flight and airport welcome. Full passport details are required for every guest.',
                'pricing_note' => 'Planning price from USD 1,550 per adult sharing. Final price depends on flight inventory, travel date, room configuration and confirmed accommodation.',
                'meta_title' => '3-Day Tarangire, Serengeti & Ngorongoro Safari | Coast Rider', 'meta_description' => 'Fly from Zanzibar for a private three-day Tarangire, Serengeti and Ngorongoro safari with accommodation and park fees.',
                'highlights' => ['Tarangire elephant herds and baobab trees', 'Serengeti wildlife plains', 'Ngorongoro Crater game drive', 'Chance to look for the black rhino', 'Private 4x4 safari Land Cruiser', 'Return flights from Zanzibar'],
                'included' => ['Return Zanzibar–Arusha flights', 'All national-park entrance fees', 'Two nights’ safari accommodation', 'Meals listed in the itinerary', 'Private 4x4 safari Land Cruiser', 'Professional English-speaking guide', 'Ngorongoro Crater service fee', 'Government taxes and levies'],
                'excluded' => ['Travel insurance', 'Alcoholic and premium drinks', 'Personal expenses, tips and gratuities'],
                'itinerary' => [['Day 1 · Tarangire', 'Arrive in Arusha, drive to Tarangire for a game drive, then continue to Karatu for dinner and overnight.'], ['Day 2 · Serengeti', 'Travel through the conservation area for an extensive Serengeti game drive and tented-camp overnight.'], ['Day 3 · Ngorongoro and departure', 'Descend into the crater for wildlife viewing and lunch, then transfer for the return flight.']],
            ],
        ];
    }

    private function faqs(int $price, string $duration): array
    {
        return [
            ['What does the displayed price mean?', "It is a planning price from USD {$price} per adult. We confirm the complete quote after checking your date, flights, ages and room arrangement."],
            ['How long is this safari?', "The planned duration is {$duration}. Flight and activity times are reconfirmed before travel."],
            ['Are wildlife sightings guaranteed?', 'No. Wildlife is free-ranging, and sightings depend on season, weather, movement and park conditions.'],
            ['What information is needed to reserve flights?', 'We require each guest’s full passport name, gender, date of birth, nationality, passport number and issuing country.'],
            ['Is payment required when I send a request?', 'No. We first verify availability and send the final itinerary, inclusions and price for approval.'],
        ];
    }

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

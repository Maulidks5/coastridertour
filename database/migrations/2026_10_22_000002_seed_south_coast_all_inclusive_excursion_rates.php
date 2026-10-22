<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rates = [
            // slug, title, duration, category, image, 1 adult, 2 adults, 3 adults, 4+ adults, child
            ['stone-town-tour', 'Historic Stone Town Tour', 'Half Day', 'culture-local-life', 'village-tour.webp', 152, 89, 69, 57, 29],
            ['spice-tour', 'Zanzibar Spice Farm Tour', 'Half Day', 'culture-local-life', 'spice-tour.webp', 135, 72, 50, 39, 20],
            ['prison-island', 'Prison Island & Giant Tortoises', 'Half Day', 'island-ocean-tours', 'prison-island.webp', 190, 114, 86, 71, 36],
            ['jozani-forest', 'Jozani Forest & Red Colobus', 'Half Day', 'nature-wildlife', 'jozani-forest.webp', 88, 51, 41, 35, 18],
            ['muyuni-village-tour', 'Muyuni Village Tour', 'Half Day', 'culture-local-life', 'village-tour.webp', 110, 60, 45, 36, 18],
            ['swahili-food-experience', 'Swahili Food Experience', 'Half Day with Lunch', 'culture-local-life', 'swahili-cooking-class.webp', 160, 87, 63, 50, 26],
            ['taste-of-north-zanzibar', 'Taste of North Zanzibar', 'Full Day', 'beaches-coastal', 'nungwi-aquarium.webp', 212, 113, 82, 63, 32],
            ['mama-zanzibar-handcrafts', 'The Mama Zanzibar & Creative Handcrafts', 'Full Day with Lunch', 'culture-local-life', 'village-tour.webp', 134, 84, 66, 56, 23],
            ['zanzibar-traditions-people', 'Zanzibar Traditions & People', 'Full Day', 'culture-local-life', 'village-tour.webp', 134, 84, 66, 56, 27],
            ['uzi-island-muyuni-village', 'Uzi Island & Muyuni Village Tour', 'Full Day', 'culture-local-life', 'village-tour.webp', 144, 83, 64, 52, 26],
            ['behind-the-scenes-uzi-island', 'Behind the Scenes of Uzi Island', 'Full Day', 'culture-local-life', 'village-tour.webp', 164, 96, 81, 69, 35],
            ['safari-blue', 'Safari Blue Dhow Adventure', 'Full Day with Lunch', 'island-ocean-tours', 'safari-blue.webp', 194, 143, 131, 119, 58],
            ['stone-town-spice-tour', 'Stone Town & Spice Delight Tour', 'Full Day', 'culture-local-life', 'spice-tour.webp', 186, 108, 84, 68, 34],
            ['stone-town-prison-island', 'Stone Town & Prison Island Tour', 'Full Day', 'culture-local-life', 'prison-island.webp', 222, 132, 118, 106, 53],
            ['stone-town-jozani-forest', 'Stone Town & Jozani Forest Tour', 'Full Day', 'nature-wildlife', 'jozani-forest.webp', 184, 114, 92, 78, 40],
            ['spice-prison-island', 'Spice Delight & Prison Island Tour', 'Full Day', 'culture-local-life', 'spice-tour.webp', 209, 124, 96, 81, 40],
            ['spice-jozani-forest', 'Spice Delight & Jozani Forest Tour', 'Full Day', 'nature-wildlife', 'jozani-forest.webp', 174, 99, 79, 65, 33],
            ['spice-muyuni-village', 'Spice Delight & Muyuni Village Tour', 'Full Day', 'culture-local-life', 'spice-tour.webp', 158, 95, 70, 56, 28],
            ['jozani-prison-island', 'Jozani Forest & Prison Island Tour', 'Full Day', 'nature-wildlife', 'jozani-forest.webp', 200, 137, 110, 92, 43],
            ['jozani-muyuni-village', 'Jozani Forest & Muyuni Village Tour', 'Full Day', 'nature-wildlife', 'jozani-forest.webp', 185, 108, 84, 70, 35],
            ['stone-town-freddie-mercury', 'Historic Stone Town & Freddie Mercury Museum', 'Half Day', 'culture-local-life', 'village-tour.webp', 162, 99, 79, 67, 39],
            ['masingini-forest-private-tour', 'Masingini Forest Private Hiking Tour', 'Half Day', 'nature-wildlife', 'jozani-forest.webp', 179, 93, 64, 50, 28],
            ['private-sunset-dinner-cruise', 'Zanzibar Private Sunset Dinner Cruise', 'Half Day (Evening)', 'island-ocean-tours', 'hero-zanzibar-dhow.webp', 525, 263, 175, 135, 50],
        ];

        $categories = DB::table('tour_categories')->pluck('id', 'slug');
        $sort = (int) DB::table('tours')->max('sort_order');
        $pricingNote = 'Private all-inclusive rate from South and South East Coast hotels. Includes return transport, professional guide and applicable entrance fees. Other pickup areas may require a supplement; the final total is confirmed before payment.';
        $pickup = 'Return pickup is included from listed South and South East Coast hotels. For Stone Town, North, North East or other areas, share your hotel so we can confirm any transfer adjustment.';

        foreach ($rates as $index => [$slug, $title, $duration, $categorySlug, $image, $one, $two, $three, $fourPlus, $child]) {
            if (! isset($categories[$categorySlug])) continue;

            $existing = DB::table('tours')->where('slug', $slug)->first();
            $pricing = [
                'duration' => $duration,
                'price' => $fourPlus,
                'shared_available' => false,
                'private_available' => true,
                'private_pricing_model' => 'per_person_tiered',
                'private_1_person_price' => $one,
                'private_2_person_price' => $two,
                'private_3_person_price' => $three,
                'private_4_plus_person_price' => $fourPlus,
                'private_child_price' => $child,
                'private_infant_price' => 0,
                'private_max_online_guests' => 10,
                'large_group_quote' => true,
                'pricing_note' => $pricingNote,
                'pickup_details' => $pickup,
                'updated_at' => now(),
            ];

            if ($existing) {
                DB::table('tours')->where('id', $existing->id)->update($pricing);
                $tourId = $existing->id;
            } else {
                $excerpt = $duration.' private Zanzibar experience with transport, a professional guide and applicable entrance fees included.';
                $tourId = DB::table('tours')->insertGetId([
                    ...$pricing,
                    'tour_category_id' => $categories[$categorySlug],
                    'experience_type' => 'zanzibar_tour',
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'description' => $excerpt.' We plan the route and timing around your hotel and group size, then confirm availability and the final total before payment.',
                    'location' => 'Zanzibar',
                    'currency' => 'USD',
                    'rating' => 0,
                    'reviews_count' => 0,
                    'cover_image_path' => '/images/experiences/'.$image,
                    'minimum_age' => null,
                    'difficulty' => null,
                    'is_featured' => false,
                    'is_popular' => false,
                    'is_published' => true,
                    'sort_order' => ++$sort,
                    'meta_title' => $title.' | Coast Rider Tour',
                    'meta_description' => $excerpt,
                    'created_at' => now(),
                ]);
            }

            $included = [
                'Private return transport from covered South and South East Coast hotels',
                'Professional local tour guide',
                'Applicable entrance and activity fees',
                'Government taxes and service charges',
            ];
            if (str_contains(strtolower($duration), 'lunch')) $included[] = 'Lunch as described in the itinerary';
            foreach ($included as $position => $label) {
                DB::table('tour_inclusions')->updateOrInsert(
                    ['tour_id' => $tourId, 'label' => $label],
                    ['is_included' => true, 'sort_order' => $position + 1, 'updated_at' => now(), 'created_at' => now()],
                );
            }
            foreach (['Tips and gratuities', 'Personal expenses', 'Food and drinks unless specifically listed', 'Pickup supplements outside the covered hotel zone'] as $position => $label) {
                DB::table('tour_inclusions')->updateOrInsert(
                    ['tour_id' => $tourId, 'label' => $label],
                    ['is_included' => false, 'sort_order' => 10 + $position, 'updated_at' => now(), 'created_at' => now()],
                );
            }
        }

        $this->upsertDesignYourDay($categories, $sort, $pricingNote, $pickup);
    }

    private function upsertDesignYourDay($categories, int $sort, string $pricingNote, string $pickup): void
    {
        if (! isset($categories['culture-local-life'])) return;
        $slug = 'design-your-day-private-tour';
        $existing = DB::table('tours')->where('slug', $slug)->first();
        $values = [
            'duration' => 'Full Day', 'price' => 239, 'shared_available' => false, 'private_available' => true,
            'private_pricing_model' => 'group_total', 'private_1_2_price' => 239, 'private_3_4_price' => 239,
            'private_5_6_price' => 239, 'private_7_10_price' => null, 'large_group_quote' => true,
            'pricing_note' => $pricingNote.' The USD 239 Design Your Day rate covers one private group of up to 6 guests.',
            'pickup_details' => $pickup, 'updated_at' => now(),
        ];
        if ($existing) {
            DB::table('tours')->where('id', $existing->id)->update($values);
            $tourId = $existing->id;
        } else {
            $tourId = DB::table('tours')->insertGetId([
                ...$values, 'tour_category_id' => $categories['culture-local-life'], 'experience_type' => 'zanzibar_tour',
                'title' => 'Design Your Day Private Tour', 'slug' => $slug,
                'excerpt' => 'A flexible full-day private Zanzibar tour designed around your interests for up to six guests.',
                'description' => 'Choose the places and experiences that matter most to your group. We confirm the practical route, included admissions and final itinerary before payment.',
                'location' => 'Zanzibar',
                'currency' => 'USD', 'rating' => 0, 'reviews_count' => 0, 'cover_image_path' => '/images/experiences/hero-zanzibar.webp',
                'minimum_age' => null, 'difficulty' => null, 'is_featured' => false, 'is_popular' => false,
                'is_published' => true, 'sort_order' => $sort + 1, 'meta_title' => 'Design Your Day Private Tour | Coast Rider Tour',
                'meta_description' => 'A flexible private Zanzibar day tour for groups of up to six guests.', 'created_at' => now(),
            ]);
        }

        foreach (['Private return transport from covered South and South East Coast hotels', 'Professional local tour guide', 'Agreed entrance fees in the confirmed itinerary'] as $position => $label) {
            DB::table('tour_inclusions')->updateOrInsert(
                ['tour_id' => $tourId, 'label' => $label],
                ['is_included' => true, 'sort_order' => $position + 1, 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }

    public function down(): void
    {
        // Production catalogue data is intentionally retained if this migration is rolled back.
    }
};

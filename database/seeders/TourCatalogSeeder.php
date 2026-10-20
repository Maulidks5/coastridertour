<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            TourCategory::query()->update(['is_active' => false]);

            $categoryImages = [
                'island-ocean-tours' => '/images/experiences/safari-blue.webp',
                'nature-wildlife' => '/images/experiences/jozani-forest.webp',
                'culture-local-life' => '/images/experiences/village-tour.webp',
                'beaches-coastal' => '/images/experiences/kendwa-beach.webp',
                'water-sports' => '/images/experiences/kitesurfing.webp',
                'tanzania-safaris' => '/images/experiences/tanzania-safaris.webp',
            ];

            $categories = collect([
                ['name' => 'Island & Ocean Tours', 'slug' => 'island-ocean-tours', 'description' => 'Island escapes, sandbanks, dhow cruises and ocean adventures.', 'sort_order' => 1],
                ['name' => 'Nature & Wildlife', 'slug' => 'nature-wildlife', 'description' => 'Forests, caves, butterflies and Zanzibar wildlife experiences.', 'sort_order' => 2],
                ['name' => 'Culture & Local Life', 'slug' => 'culture-local-life', 'description' => 'Spices, villages, food and authentic local experiences.', 'sort_order' => 3],
                ['name' => 'Beaches & Coastal', 'slug' => 'beaches-coastal', 'description' => 'Relaxed beach days and memorable coastal destinations.', 'sort_order' => 4],
                ['name' => 'Water Sports', 'slug' => 'water-sports', 'description' => 'Active ocean experiences for beginners and adventure seekers.', 'sort_order' => 5],
                ['name' => 'Tanzania Safaris', 'slug' => 'tanzania-safaris', 'description' => 'Custom wildlife safari packages across mainland Tanzania.', 'sort_order' => 6],
            ])->mapWithKeys(function (array $category) use ($categoryImages): array {
                $model = TourCategory::query()->updateOrCreate(
                    ['slug' => $category['slug']],
                    [...$category, 'image_path' => $categoryImages[$category['slug']], 'is_active' => true],
                );

                return [$category['slug'] => $model];
            });

            $catalog = [
                $this->tour('Prison Island & Giant Tortoises', 'prison-island', 'island-ocean-tours', 'Visit historic Prison Island and meet its famous giant Aldabra tortoises.', 'Half Day', 'Stone Town', ['Giant tortoise sanctuary', 'Historic island visit', 'Scenic boat transfer']),
                $this->tour('Nakupenda Sandbank Escape', 'nakupenda-beach', 'island-ocean-tours', 'Unwind on a white sandbank surrounded by clear Indian Ocean water.', 'Full Day', 'Zanzibar Coast', ['White sandbank', 'Swimming and relaxation', 'Ocean picnic experience'], popular: true),
                $this->tour('Safari Blue Dhow Adventure', 'safari-blue', 'island-ocean-tours', 'Sail Menai Bay by traditional dhow with snorkeling and sandbank stops.', 'Full Day', 'Fumba Peninsula', ['Traditional dhow sailing', 'Guided snorkeling', 'Sandbank relaxation', 'Seafood lunch'], featured: true, popular: true),
                $this->tour('Mnemba Atoll Snorkeling', 'mnemba-island', 'island-ocean-tours', 'Explore the clear waters and coral reefs around Mnemba Atoll.', 'Full Day', 'Northeast Zanzibar', ['Guided snorkeling', 'Coral reef experience', 'Boat journey around Mnemba'], popular: true),
                $this->tour('Sport Fishing Adventure', 'fishing-game-tour', 'island-ocean-tours', 'Head offshore with a local crew for a dedicated sport fishing experience.', 'Flexible', 'Zanzibar Coast', ['Local fishing crew', 'Fishing equipment', 'Private ocean experience']),
                $this->tour('Snapper Fishing Experience', 'snapper-fishing', 'island-ocean-tours', 'A focused fishing trip targeting snapper with an experienced local crew.', 'Flexible', 'Zanzibar Coast', ['Targeted snapper fishing', 'Fishing equipment', 'Local crew']),

                $this->tour('Jozani Forest & Red Colobus', 'jozani-forest', 'nature-wildlife', 'Walk through Jozani Forest and encounter Zanzibar’s endemic red colobus monkeys.', 'Half Day', 'Jozani', ['Red colobus monkeys', 'Guided forest walk', 'Mangrove boardwalk'], popular: true),
                $this->tour('Nungwi Natural Aquarium', 'nungwi-aquarium', 'nature-wildlife', 'Discover a natural marine aquarium experience on Zanzibar’s northern coast.', 'Half Day', 'Nungwi', ['Natural lagoon setting', 'Marine life experience', 'Northern coast visit']),
                $this->tour('Salaam Cave Swimming Experience', 'salaam-cave', 'nature-wildlife', 'Visit Salaam Cave for a refreshing swim in a memorable natural setting.', 'Half Day', 'Zanzibar', ['Cave swimming', 'Natural surroundings', 'Guided local visit']),
                $this->tour('Mangapwani Coral Cave', 'mangapwani-coral-cave', 'nature-wildlife', 'Explore Mangapwani’s coral cave and learn about the area’s natural and local history.', 'Half Day', 'Mangapwani', ['Coral cave exploration', 'Local history', 'Guided visit']),
                $this->tour('Zanzibar Butterfly Tour', 'butterfly-tour', 'nature-wildlife', 'Enjoy a relaxed guided visit focused on Zanzibar’s colorful butterflies and conservation.', 'Half Day', 'Zanzibar', ['Butterfly encounters', 'Conservation stories', 'Family-friendly visit']),

                $this->tour('Zanzibar Spice Farm Tour', 'spice-tour', 'culture-local-life', 'See, smell and taste the spices that made Zanzibar world famous.', 'Half Day', 'Zanzibar', ['Guided spice walk', 'Tropical fruit tasting', 'Local history'], popular: true),
                $this->tour('Authentic Village Experience', 'village-tour', 'culture-local-life', 'Connect with everyday island life through a respectful guided village visit.', 'Half Day', 'Zanzibar', ['Local community visit', 'Island traditions', 'Guided cultural exchange']),
                $this->tour('Swahili Cooking Class', 'swahili-cooking-class', 'culture-local-life', 'Learn to prepare flavorful Swahili dishes in a welcoming local cooking experience.', 'Half Day', 'Zanzibar', ['Hands-on cooking', 'Local ingredients', 'Shared Swahili meal']),

                $this->tour('Kendwa Beach Experience', 'kendwa-beach', 'beaches-coastal', 'Spend time on Kendwa’s beautiful coast with swimming, relaxation and sunset views.', 'Flexible', 'Kendwa', ['Beach relaxation', 'Swimming', 'Sunset views']),
                $this->tour('The Rock Restaurant Experience', 'the-rock-restaurant', 'beaches-coastal', 'Combine a scenic coastal journey with a visit to Zanzibar’s iconic Rock Restaurant.', 'Half Day', 'Michamvi Pingwe', ['Scenic coastal transfer', 'Iconic restaurant setting', 'Flexible dining arrangements']),

                $this->tour('Glass Kayaking Experience', 'glass-kayaking', 'water-sports', 'Glide over clear coastal water in a transparent kayak.', '1–2 Hours', 'Zanzibar Coast', ['Transparent kayak', 'Coastal views', 'Beginner-friendly'], type: 'water_sport', minimumAge: 8, difficulty: 'Easy'),
                $this->tour('Stand-Up Paddleboarding', 'stand-up-paddleboarding', 'water-sports', 'Enjoy a calm-water paddleboarding session with guidance available for beginners.', '1–2 Hours', 'Zanzibar Coast', ['Paddleboard and safety gear', 'Beginner guidance', 'Coastal session'], type: 'water_sport', minimumAge: 10, difficulty: 'Easy'),
                $this->tour('Kitesurfing Experience', 'kitesurfing', 'water-sports', 'Experience Zanzibar’s famous winds with a guided kitesurfing session.', 'Flexible', 'Zanzibar Coast', ['Instructor support', 'Kitesurf equipment', 'Skill-based session'], type: 'water_sport', minimumAge: 12, difficulty: 'Moderate'),
                $this->tour('Spearfishing Experience', 'spearfishing', 'water-sports', 'Join an experienced local guide for a responsible spearfishing experience.', 'Flexible', 'Zanzibar Coast', ['Experienced guide', 'Required equipment', 'Safety briefing'], type: 'water_sport', minimumAge: 18, difficulty: 'Advanced'),
                $this->tour('Jet Ski Adventure', 'jet-ski-adventure', 'water-sports', 'Enjoy an exciting guided jet ski ride along Zanzibar’s coast.', 'Flexible', 'Zanzibar Coast', ['Jet ski session', 'Safety equipment', 'Guide briefing'], type: 'water_sport', minimumAge: 16, difficulty: 'Moderate', popular: true),
                $this->tour('Water Sports Combo', 'water-sports-tour', 'water-sports', 'Combine selected water activities in one flexible coastal adventure.', 'Flexible', 'Zanzibar Coast', ['Choice of water activities', 'Safety equipment', 'Flexible package'], type: 'water_sport', minimumAge: 10, difficulty: 'Varies'),

                $this->tour('Spice Farm, Prison Island & Stone Town', 'spice-prison-stone-town', 'culture-local-life', 'Discover Zanzibar’s spices, island history and Stone Town heritage in one thoughtfully planned private day.', 'Full Day', 'Stone Town & Zanzibar', ['Guided spice farm visit', 'Giant tortoises on Prison Island', 'Stone Town heritage walk'], featured: true, popular: true),
                $this->tour('Prison Island & Nakupenda Sandbank', 'prison-island-nakupenda', 'island-ocean-tours', 'Meet the giant tortoises before relaxing, swimming and enjoying the turquoise water at Nakupenda Sandbank.', 'Full Day', 'Stone Town Coast', ['Prison Island boat trip', 'Giant tortoise sanctuary', 'Nakupenda swimming and sandbank time'], featured: true, popular: true),
                $this->tour('Nungwi Turtles & Sunset Dhow', 'nungwi-turtles-sunset', 'beaches-coastal', 'Combine Nungwi’s turtle sanctuary and northern beaches with a memorable sunset dhow experience.', 'Full Day', 'North Zanzibar', ['Turtle sanctuary visit', 'Time on Zanzibar’s north coast', 'Traditional sunset dhow experience'], featured: true, popular: true),
                $this->tour('Tanzania Safari & Zanzibar Beach Escape', 'safari-zanzibar-beach-escape', 'tanzania-safaris', 'Pair a tailor-made mainland wildlife safari with relaxed beach days in Zanzibar, planned as one seamless journey.', '7+ Days', 'Mainland Tanzania & Zanzibar', ['Tailor-made Tanzania safari', 'Zanzibar beach stay', 'Coordinated transfers and itinerary'], type: 'safari', featured: true, popular: true),

                $this->tour('Tanzania Safari Packages', 'tanzania-safaris', 'tanzania-safaris', 'Plan a custom mainland wildlife safari based on your dates, preferred parks and travel style.', 'Multi-day', 'Mainland Tanzania', ['Custom safari planning', 'Wildlife destinations', 'Flexible accommodation options', 'Transfer coordination'], type: 'safari', featured: true, popular: true),
            ];

            $activeSlugs = collect($catalog)->pluck('slug');
            Tour::query()->whereNotIn('slug', $activeSlugs)->update(['is_published' => false]);

            $preserveUploadedCover = ['nakupenda-beach'];
            $packageCovers = [
                'spice-prison-stone-town' => '/images/experiences/spice-tour.webp',
                'prison-island-nakupenda' => '/images/experiences/prison-island.webp',
                'nungwi-turtles-sunset' => '/images/experiences/nungwi-aquarium.webp',
                'safari-zanzibar-beach-escape' => '/images/experiences/tanzania-safaris.webp',
            ];

            foreach ($catalog as $index => $data) {
                $category = $categories->get($data['category']);
                $highlights = $data['highlights'];
                unset($data['category'], $data['highlights']);

                $tour = Tour::query()->updateOrCreate(
                    ['slug' => $data['slug']],
                    [
                        ...$data,
                        'tour_category_id' => $category->id,
                        'price' => 0,
                        'currency' => 'USD',
                        'rating' => 0,
                        'reviews_count' => 0,
                        'cover_image_path' => in_array($data['slug'], $preserveUploadedCover, true)
                            ? (Tour::query()->where('slug', $data['slug'])->value('cover_image_path') ?: '/images/experiences/hero-zanzibar.webp')
                            : ($packageCovers[$data['slug']] ?? '/images/experiences/'.$data['slug'].'.webp'),
                        'pickup_details' => 'Pickup availability and exact timing are confirmed after the booking request.',
                        'is_published' => true,
                        'sort_order' => $index + 1,
                        'meta_title' => $data['title'].' | Coast Rider Tour',
                        'meta_description' => $data['excerpt'],
                    ],
                );

                $tour->highlights()->delete();
                $tour->highlights()->createMany(
                    collect($highlights)->map(fn (string $label, int $position): array => [
                        'label' => $label,
                        'sort_order' => $position + 1,
                    ])->all(),
                );

                $tour->inclusions()->delete();
                $tour->inclusions()->createMany([
                    ['label' => 'Professional local guide or instructor', 'is_included' => true, 'sort_order' => 1],
                    ['label' => 'Required activity equipment where applicable', 'is_included' => true, 'sort_order' => 2],
                    ['label' => 'Pickup when confirmed for your location', 'is_included' => true, 'sort_order' => 3],
                    ['label' => 'Personal expenses', 'is_included' => false, 'sort_order' => 4],
                    ['label' => 'Tips and gratuities', 'is_included' => false, 'sort_order' => 5],
                ]);

                $tour->itineraryItems()->delete();
                $tour->itineraryItems()->createMany([
                    ['time_label' => 'Start', 'title' => 'Pickup or meeting point', 'description' => 'Meet the local team at the confirmed time and location.', 'sort_order' => 1],
                    ['time_label' => null, 'title' => 'Guided experience', 'description' => 'Enjoy the planned activities with local support and guidance.', 'sort_order' => 2],
                    ['time_label' => 'Finish', 'title' => 'Return or onward plans', 'description' => 'Complete the experience and return as arranged.', 'sort_order' => 3],
                ]);

                $tour->faqs()->delete();
                $tour->faqs()->createMany([
                    ['question' => 'What information is needed for an accurate quote?', 'answer' => 'Share your travel dates, group size, hotel or pickup area, and whether you prefer a private or shared experience.', 'sort_order' => 1],
                    ['question' => 'Can this experience be customised?', 'answer' => 'Yes. Timing, pickup arrangements and selected activities can be adjusted around your group and availability.', 'sort_order' => 2],
                    ['question' => 'Is payment required when I send a request?', 'answer' => 'No. We first confirm availability, final inclusions and price before any payment is requested.', 'sort_order' => 3],
                ]);
            }
        });
    }

    private function tour(
        string $title,
        string $slug,
        string $category,
        string $excerpt,
        string $duration,
        string $location,
        array $highlights,
        string $type = 'zanzibar_tour',
        ?int $minimumAge = null,
        ?string $difficulty = null,
        bool $featured = false,
        bool $popular = false,
    ): array {
        return [
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'experience_type' => $type,
            'excerpt' => $excerpt,
            'description' => $excerpt.' Our team can tailor timing, pickup and private arrangements to your travel plans.',
            'duration' => $duration,
            'location' => $location,
            'minimum_age' => $minimumAge,
            'difficulty' => $difficulty,
            'is_featured' => $featured,
            'is_popular' => $popular,
            'highlights' => $highlights,
        ];
    }
}

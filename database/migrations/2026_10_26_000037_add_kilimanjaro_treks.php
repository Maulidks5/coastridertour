<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('tour_categories')->updateOrInsert(
            ['slug' => 'kilimanjaro-treks'],
            ['name' => 'Kilimanjaro Treks', 'description' => 'Guided Kilimanjaro day hikes and summit routes arranged around your fitness, time and preferred pace.', 'image_path' => '/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ) ?: null;
        $categoryId = DB::table('tour_categories')->where('slug', 'kilimanjaro-treks')->value('id');
        if (! $categoryId) return;

        $treks = [
            ['kilimanjaro-day-hike', 'Kilimanjaro Day Hike', '1 Day', 'Marangu Route', 'Walk through Kilimanjaro’s lower rainforest with a licensed mountain guide, without attempting the summit.', ['Guided rainforest hike', 'No summit attempt', 'Moshi transfers available']],
            ['kilimanjaro-marangu-route', 'Kilimanjaro Marangu Route', '6 Days / 5 Nights', 'Marangu Route', 'A classic Kilimanjaro summit itinerary using the mountain’s hut accommodation and an extra acclimatisation day.', ['Mountain hut accommodation', 'Acclimatisation day', 'Uhuru Peak summit attempt']],
            ['kilimanjaro-machame-route', 'Kilimanjaro Machame Route', '7 Days / 6 Nights', 'Machame Route', 'A scenic camping route with varied landscapes and a seven-day schedule designed to support gradual acclimatisation.', ['Scenic southern route', 'Tented mountain camps', 'Gradual acclimatisation']],
            ['kilimanjaro-lemosho-route', 'Kilimanjaro Lemosho Route', '8 Days / 7 Nights', 'Lemosho Route', 'A longer, scenic approach across the Shira Plateau with additional time for acclimatisation before the summit attempt.', ['Shira Plateau scenery', 'Longer acclimatisation', 'Remote western approach']],
        ];

        foreach ($treks as $index => [$slug, $title, $duration, $location, $excerpt, $highlights]) {
            DB::table('tours')->updateOrInsert(['slug' => $slug], [
                'tour_category_id' => $categoryId, 'experience_type' => 'kilimanjaro', 'title' => $title,
                'excerpt' => $excerpt,
                'description' => $excerpt."\n\nThis trek is arranged through a licensed Tanzanian mountain operator with qualified guides and the required support crew. Your final proposal confirms the route, permits, park and rescue fees, accommodation, meals, equipment arrangements and safety plan before payment. Summit success is never guaranteed and always depends on health, acclimatisation, weather and guide decisions.",
                'duration' => $duration, 'location' => $location, 'price' => 0, 'currency' => 'USD',
                'shared_available' => false, 'private_available' => true, 'private_pricing_model' => 'quote_only',
                'large_group_quote' => true, 'pricing_note' => 'Request a personalised quote based on dates, group size, route, crew, park fees, accommodation and equipment needs.',
                'rating' => 0, 'reviews_count' => 0,
                'cover_image_path' => '/images/tours/custom-tanzania-safari/gallery/kilimanjaro-elephant.webp',
                'pickup_details' => 'Transfers are arranged from Kilimanjaro International Airport, Moshi or Arusha as listed in the confirmed itinerary.',
                'minimum_age' => null, 'difficulty' => $slug === 'kilimanjaro-day-hike' ? 'Moderate' : 'Challenging',
                'is_featured' => true, 'is_popular' => in_array($slug, ['kilimanjaro-machame-route', 'kilimanjaro-lemosho-route'], true),
                'is_published' => true, 'sort_order' => $index + 1,
                'meta_title' => $title.' | Coast Rider Tour', 'meta_description' => $excerpt,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            $tourId = DB::table('tours')->where('slug', $slug)->value('id');
            DB::table('tour_highlights')->where('tour_id', $tourId)->delete();
            foreach ($highlights as $order => $label) DB::table('tour_highlights')->insert(['tour_id' => $tourId, 'label' => $label, 'sort_order' => $order + 1, 'created_at' => now(), 'updated_at' => now()]);

            DB::table('tour_inclusions')->where('tour_id', $tourId)->delete();
            $included = $slug === 'kilimanjaro-day-hike'
                ? ['Licensed mountain guide', 'Park entry and applicable permit fees stated in the quote', 'Confirmed return road transfer', 'Packed lunch and drinking water']
                : ['Licensed guides and required mountain support crew', 'TANAPA permits, conservation and rescue fees stated in the quote', 'Mountain huts or tents according to the route', 'Meals and treated drinking water on the mountain', 'Confirmed airport, hotel and gate transfers'];
            $excluded = ['International flights, visa and travel insurance', 'Personal trekking clothing and equipment unless listed', 'Tips for guides, cooks and porters', 'Medical treatment, evacuation beyond included rescue cover and personal expenses'];
            foreach ([...$included, ...$excluded] as $order => $label) DB::table('tour_inclusions')->insert(['tour_id' => $tourId, 'label' => $label, 'is_included' => $order < count($included), 'sort_order' => $order + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        DB::table('tours')->where('experience_type', 'kilimanjaro')->delete();
        DB::table('tour_categories')->where('slug', 'kilimanjaro-treks')->delete();
    }
};

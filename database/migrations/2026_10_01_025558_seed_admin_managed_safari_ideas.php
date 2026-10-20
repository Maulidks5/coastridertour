<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('tour_categories')->where('slug', 'tanzania-safaris')->value('id');
        if (! $categoryId) return;

        DB::table('tours')->where('experience_type', 'safari')->update(['is_featured' => false]);

        $ideas = [
            ['mikumi-day-safari', 'Mikumi Day Safari', '1 Day', 'Mikumi', '/images/experiences/safari-mikumi-day.webp', 'A compact wildlife escape from Zanzibar or Dar es Salaam.', ['Game drive', 'Elephants & giraffes']],
            ['mikumi-two-day-safari', 'Mikumi Safari', '2 Days', 'Mikumi', '/images/experiences/safari-mikumi-2-day.webp', 'More time for game drives and a relaxed overnight stay.', ['Extended game drives', 'Overnight stay']],
            ['nyerere-three-day-safari', 'Nyerere Safari', '3 Days', 'Nyerere', '/images/experiences/safari-nyerere.webp', 'River landscapes, wildlife and a quieter southern-circuit experience.', ['Rufiji landscapes', 'Southern circuit']],
            ['serengeti-ngorongoro-safari', 'Serengeti & Ngorongoro', '3–5 Days', 'Northern Tanzania', '/images/experiences/tanzania-safaris.webp', 'A classic northern-circuit journey shaped around your schedule.', ['Serengeti wildlife', 'Ngorongoro Crater']],
            ['northern-circuit-safari', 'Northern Circuit', '4–7 Days', 'Northern Tanzania', '/images/experiences/safari-ngorongoro.webp', 'Combine Tarangire, Manyara, Ngorongoro and Serengeti.', ['Multiple parks', 'Flexible route']],
            ['custom-tanzania-safari', 'Custom Tanzania Safari', 'Your Choice', 'Tanzania', '/images/experiences/safari-tarangire.webp', 'Choose your parks, comfort level, pace and starting point.', ['Tailor-made route', 'Your comfort level']],
        ];

        foreach ($ideas as $position => [$slug, $title, $duration, $location, $image, $excerpt, $highlights]) {
            DB::table('tours')->updateOrInsert(['slug' => $slug], [
                'tour_category_id' => $categoryId,
                'experience_type' => 'safari',
                'title' => $title,
                'excerpt' => $excerpt,
                'description' => $excerpt.' Share your dates, group size and preferred accommodation so our team can prepare the right route and quote.',
                'duration' => $duration,
                'location' => $location,
                'price' => 0,
                'currency' => 'USD',
                'rating' => 0,
                'reviews_count' => 0,
                'cover_image_path' => $image,
                'pickup_details' => 'Transfers, park sequence and pickup details are confirmed in your personal itinerary.',
                'minimum_age' => null,
                'difficulty' => null,
                'is_featured' => true,
                'is_popular' => $position < 3,
                'is_published' => true,
                'sort_order' => $position + 1,
                'meta_title' => $title.' | Coast Rider Tour',
                'meta_description' => $excerpt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $tourId = DB::table('tours')->where('slug', $slug)->value('id');
            DB::table('tour_highlights')->where('tour_id', $tourId)->delete();
            foreach ($highlights as $order => $label) {
                DB::table('tour_highlights')->insert(['tour_id' => $tourId, 'label' => $label, 'sort_order' => $order + 1, 'created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        DB::table('tours')->whereIn('slug', [
            'mikumi-day-safari', 'mikumi-two-day-safari', 'nyerere-three-day-safari',
            'serengeti-ngorongoro-safari', 'northern-circuit-safari', 'custom-tanzania-safari',
        ])->delete();
    }
};
